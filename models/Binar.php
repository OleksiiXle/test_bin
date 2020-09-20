<?php

namespace app\models;

/**
 * This is the model class for table "binar".
 *
 * @property int $id
 * @property int $parent_id
 * @property int $position
 * @property string $path
 * @property int $level
 * @property string|null $name
 */
class Binar extends \yii\db\ActiveRecord
{
    const POSITION_ROOT = 0;
    const POSITION_LEFT = 1;
    const POSITION_RIGHT = 2;
    const POSITIONS = [
        self::POSITION_LEFT => 'Left',
        self::POSITION_RIGHT => 'Right',
    ];

    public $result = [
        'status' => false,
        'data' => 'Unknown error'
    ];

    private $_nodeInfo;
    private $_allParents;
    private $_allChildren;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'binar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'position'], 'required'],
            [['parent_id', 'position', 'level'], 'integer'],
            [['path'], 'string', 'max' => 12288],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор',
            'parent_id' => 'ID родителя',
            'position' => 'Позиция',
            'path' => 'Путь',
            'level' => 'Уровень',
            'name' => 'Название',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(self::class, ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'parent_id']);
    }

    /**
     * @return array
     */
    public function getNodeInfo()
    {
        $this->_nodeInfo = [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'hasChildren'   => (count($this->children) > 0),
        ];

        return $this->_nodeInfo;
    }

    /**
     * @return mixed
     */
    public function getAllParents()
    {
        $parentsIds = explode('.', $this->path);
        $this->_allParents = self::find()
            ->where(['IN', 'id', $parentsIds])
            ->andWhere(['!=', 'id', $this->id])
            ->orderBy('path')
            ->all();

        return $this->_allParents;
    }

    /**
     * @return mixed
     */
    public function getAllChildren()
    {
        $this->_allChildren = self::find()
            ->where(['LIKE', 'path', $this->path . '.'])
            ->orderBy('path')
            ->all();

        return $this->_allChildren;
    }

    /**
     * @return string
     */
    public function showAllChildren()
    {
        $result = '';
        foreach ($this->allChildren as $binar) {
            $result .= $binar->path . '<br>';
        }

        return $result;
    }

    /**
     * @return string
     */
    public function showAllParents()
    {
        $result = '';
        foreach ($this->allParents as $binar) {
            $result .= $binar->path . '<br>';
        }

        return $result;
    }

    /**
     *
     */
    public function setPathAndLevel()
    {
        $this->path = (string)$this->id;
        $this->level = 1;
        $parent = $this->parent;
        while (!empty($parent)){
            $this->path = $parent->id . '.' . $this->path;
            $this->level++;
            $parent = $parent->parent;
        }
        $this->name = 'Binar ' . $this->path ;
    }

    /**
     * Записывает в массив $target идентификаторы всех потомков
     * @param $parent_id
     * @param $target
     * @return bool
     */


    /**
     * Writes the identifiers of all descendants to the $target array
     * @param $parent_id
     * @param $target
     * @return bool
     */
    public static function getChildrenIds($parent_id, &$target)
    {
        $children = (new \yii\db\Query)
            ->select(['id', 'parent_id'])
            ->from(self::tableName())
            ->where(['parent_id' => $parent_id])
            ->orderBy('id')
            ->all();
        if (count($children) > 0) {
            foreach ($children as $child) {
                $target[] = $child['id'];
                self::getChildrenIds($child['id'], $target);
            }
        }
    }

    /**
     * Creating a binar
     * @param $parent_id
     * @param $position
     * @return Binar
     */
    public static function makeBinar($parent_id, $position)
    {
        $binar = new self();
        $binar->parent_id = $parent_id;
        $binar->position = $position;
        $binar->save();

        $binar->setPathAndLevel();
        $binar->save();

        return $binar;
    }

    /**
     * Create left and right child of a binar
     * @param $parent_id
     * @return array
     */
    public static function makeBinarsChildren($parent_id)
    {
        $result[] = self::makeBinar($parent_id, Binar::POSITION_LEFT);
        $result[] = self::makeBinar($parent_id, Binar::POSITION_RIGHT);

        return $result;

    }

    /**
     * Automatic filling of binar up to level 5
     * @param int $parent_id
     */
    public static function makeTestBinars($parent_id=0)
    {
        $parentBinar = self::findOne($parent_id);
        if (empty($parentBinar)){
            $binar = self::makeBinar($parent_id, self::POSITION_ROOT);
            $children = self::makeBinarsChildren($binar->id);
            foreach ($children as $child) {
                self::makeTestBinars($child->id);
            }
        } else {
            if ($parentBinar->level <= 4) {
                switch (count($parentBinar->children)) {
                    case 0:
                        self::makeBinar($parent_id, Binar::POSITION_LEFT);
                        self::makeTestBinars($parent_id);
                        break;
                    case 1:
                        self::makeBinar($parent_id, Binar::POSITION_RIGHT);
                        self::makeTestBinars($parent_id);
                        break;
                    case 2:
                        $children = $parentBinar->children;
                        foreach ($children as $child) {
                            self::makeTestBinars($child->id);
                        }
                        break;
                }
            }
        }
    }

    /**
     * @param null $condition
     * @param array $params
     * @return int
     * @throws \yii\db\Exception
     */
    public static function deleteAll($condition = null, $params = [])
    {
        $result = parent::deleteAll($condition, $params);
        if ($condition == null) {
            \Yii::$app->db->createCommand('ALTER TABLE binar AUTO_INCREMENT=1;')->execute();
        }

        return  $result;
    }

    /**
     * Adding a child to a binar
     * @param $data
     * @return bool
     */
    public function appendChild($data)
    {
        switch (count($this->children)) {
            case 1:
                $alreadyExists = Binar::find()
                    ->where(['parent_id' => $data['id']])
                    ->andWhere(['position' => $data['position']])
                    ->count();
                if ($alreadyExists) {
                    $this->result['data'] = 'Такой потомок уже есть';
                    return false;
                }
            case 0:
                $newBinar = self::makeBinar($this->id, $data['position']);
                if (!$newBinar->hasErrors()){
                    $this->result = [
                        'status' => true,
                        'data' => [
                            'newNode' => $newBinar->nodeInfo,
                            'parentNode' => $newBinar->parent->nodeInfo,
                        ]
                    ];
                    return true;
                } else {
                    $this->result['data'] = 'Не удалось добавить потомка';
                    return false;
                }
            case 2:
                $this->result['data'] = 'Уже есть два потомка';
                return false;
        }
    }

    /**
     * @param $id
     * @return array
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public static function deleteWithChildren($id)
    {
        $result['status'] = false;
        $result['data'] = ['error'];
        $binarDel = self::findOne($id);
        $parent_id = $binarDel->parent_id;

        $childrenIds = [];
        self::getChildrenIds($binarDel->id, $childrenIds);
        if (count($childrenIds) > 0){
            $childrenDelCount = self::deleteAll(['IN', 'id', $childrenIds]);
            if ($childrenDelCount <> count($childrenIds)){
                $result['data'] = 'Не удалось удалить потомков';
                return $result;
            }
        }

        if ($binarDel->delete() === 0) {
            $result['data'] = 'Не удалось удалить';
            return $result;
        }

        $binarParent = self::findOne($parent_id);
        if (isset($binarParent)){
            $result = [
                'status' => true,
                'data' => [
                    'node1' => [],
                    'node2' => $binarParent->nodeInfo,
                ]
            ];
        } else {
            $result = [
                'status' => true,
                'data' => [
                    'node1' => [],
                    'node2' => [],
                ]
            ];
        }

        return $result;
    }
}
