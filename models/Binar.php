<?php

namespace app\models;

use Yii;

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

    private $_leftChild;
    private $_rightChild;
    private $_nodeInfo;



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
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'position' => 'Position',
            'path' => 'Path',
            'level' => 'Level',
            'name' => 'Name',
        ];
    }

    public function getChildren()
    {
        return $this->hasMany(self::class, ['parent_id' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'parent_id']);
    }

    /**
     * @return mixed
     */
    public function getLeftChild()
    {
        $this->_leftChild = self::find()
            ->where(['parent_id' => $this->id])
            ->andWhere(['position' => self::POSITION_LEFT])
            ->one();
        return $this->_leftChild;
    }

    /**
     * @return mixed
     */
    public function getRightChild()
    {
        $this->_rightChild = self::find()
            ->where(['parent_id' => $this->id])
            ->andWhere(['position' => self::POSITION_RIGHT])
            ->one();
        return $this->_rightChild;
    }


    public function getChildrenArray()
    {
        $this->_childrenArray = [];
        foreach ($this->children as $child){
            $this->_childrenArray[] = $child->nodeInfo;
        }

        return $this->_childrenArray;
    }

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
        $this->name = 'Binar ' . $this->level . ' ' . $this->id;
    }

    /**
     * Записывает в массив $target идентификаторы всех потомков
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
            return true;
        }
    }

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

    public static function makeBinarWithChildren($parent_id, $position)
    {
        $parent_id = self::makeBinar($parent_id, $position);
        foreach ([self::POSITION_LEFT, self::POSITION_RIGHT] as $childrenPosition) {
            $result[$childrenPosition] = self::makeBinar($parent_id, $childrenPosition);
        }

        return $result;
    }

    public static function makeBinarsChildren($parent_id)
    {
        $result[] = self::makeBinar($parent_id, Binar::POSITION_LEFT);
        $result[] = self::makeBinar($parent_id, Binar::POSITION_RIGHT);

        return $result;

    }

    public static function makeTestBinars($parent_id=0)
    {
        $tmp = 1;
        $parentBinar = self::findOne($parent_id);
        if (empty($parentBinar)){
            //-- нет родителя
            $binar = self::makeBinar($parent_id, self::POSITION_ROOT);
            $children = self::makeBinarsChildren($binar->id);
            foreach ($children as $child) {
                self::makeTestBinars($child->id);
            }
        } else {
            //-- есть родитель
            if ($parentBinar->level <= 2) {
                switch (count($parentBinar->children)) {
                    case 0:
                        //-- у будущуего родителя еще нет потомков
                        $binar = self::makeBinar($parent_id, Binar::POSITION_LEFT);
                        self::makeTestBinars($parent_id);
                        break;
                    case 1:
                        //-- у родителя есть левый потомок
                        $binar = self::makeBinar($parent_id, Binar::POSITION_RIGHT);
                        self::makeTestBinars($parent_id);
                        break;
                    case 2:
                        //-- у родителя есть левый и правый потомки
                        $children = $parentBinar->children;
                        foreach ($children as $child) {
                            self::makeTestBinars($child->id);
                        }
                        break;
                }
            }
        }
        return true;
    }

    public static function deleteAll($condition = null, $params = [])
    {
        $result = parent::deleteAll($condition, $params);
        if ($condition == null) {
            \Yii::$app->db->createCommand('ALTER TABLE binar AUTO_INCREMENT=1;')->execute();
        }

        return  $result;
    }

    public function appendChild($data)
    {
        $tmp = 1;
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

    public static function deleteWithChildren($id)
    {
        $result['status'] = false;
        $result['data'] = ['error'];
        $binarDel = self::findOne($id);
        $parent_id = $binarDel->parent_id;

        //-- определить потомков
        $childrenIds = [];
        self::getChildrenIds($binarDel->id, $childrenIds);
        if (count($childrenIds) > 0){
            //-- удаляем потомков
            $childrenDelCount = self::deleteAll(['IN', 'id', $childrenIds]);
            if ($childrenDelCount <> count($childrenIds)){
                $result['data'] = 'Не удалось удалить потомков';
                return $result;
            }
        }

        //-- удаляем узел
        if ($binarDel->delete() === 0) {
            $result['data'] = 'Не удалось удалить';
            return $result;
        }

        //-- если был предок - возвращаем информацию о нем
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
