<?php

namespace app\components\models;

use app\components\DbMessageSource;
use app\models\MainModel;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "translation".
 *
 * @property int $id
 * @property int $tkey
 * @property string $category
 * @property string $language
 * @property string $message
 * @property string $links
 */
class Translation extends MainModel
{
    const NAME_PATTERN = '/^[А-ЯІЇЄҐа-яіїєґ0-9A-Za-z { } ().№ʼ,«»\'"\-]+$/u'; //--маска для нимени
    const NAME_ERROR_MESSAGE = 'Используйте буквы, цифры, пробел, кавычки, символи ( { } . , № - )'; //--сообщение об ошибке

    const LIST_CATEGORY =[
        'app' => 'app',
        'yii' => 'yii',
    ];
    const LIST_LANGUAGES = [
        'ru-RU' => 'Русский',
        'uk-UK' => 'Українська',
        'en-US' => 'English',
    ];


  //  private $_languages;

    public $messageRU = '';
    public $messageUK = '';
    public $messageEN = '';


    /**
     * @return mixed
     */
    public static function getLanguages()
    {
        $ret = self::LIST_LANGUAGES;
        return $ret;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'translation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
          //  [['tkey', ], 'required'],
            [['tkey'], 'integer'],
            [['message'], 'string'],
            [['category'], 'string', 'max' => 3],
            [['language'], 'string', 'max' => 10],
            [['links'], 'string', 'max' => 250],
            [['messageRU', 'messageUK', 'messageEN'], 'string', 'max' => 255],
            [['messageRU', 'messageUK', 'messageEN'], 'trim'],
            [['messageRU', 'messageUK', 'messageEN'], 'match', 'pattern' => self::NAME_PATTERN,
                'message' => Yii::t('app', self::NAME_ERROR_MESSAGE)],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tkey' => 'Tkey',
            'category' => Yii::t('app', 'Категория'),
            'language' => Yii::t('app', 'Язык'),
            'message' => Yii::t('app', 'Текст'),
            'messageRU' => Yii::t('app', 'Русский'),
            'messageUK' => Yii::t('app', 'Ураїнський'),
            'messageEN' => Yii::t('app', 'English'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(self::class, ['tkey' => 'tkey'])
            ->andWhere("id <> $this->id" )
            ;
    }


    public function saveTranslation()
    {
        $currentLanguage = Yii::$app->language;
        $ret = $this->validate();
        if ($ret){
            if ($this->isNewRecord){
                $nextKey = self::find()->max('tkey');
                if (!isset($nextKey)){
                    $nextKey = 1;
                } else {
                    $nextKey += 1;
                }
                if (!empty($this->messageRU)){
                    $t = new self();
                    $t->tkey = $nextKey;
                    $t->category = $this->category;
                    $t->language = 'ru-RU';
                    $t->message = $this->messageRU;
                    if (!$t->save()){
                        if ($currentLanguage == $t->language) {
                            $this->id = $t->id;
                        }
                        return false;
                    }
                    if ($currentLanguage == $t->language) {
                        $this->id = $t->id;
                    }
                }

                if (!empty($this->messageUK)){
                    $t = new self();
                    $t->tkey = $nextKey;
                    $t->category = $this->category;
                    $t->language = 'uk-UK';
                    $t->message = $this->messageUK;
                    if (!$t->save()){
                        return false;
                    }
                    if ($currentLanguage == $t->language) {
                        $this->id = $t->id;
                    }
                }

                if (!empty($this->messageEN)){
                    $t = new self();
                    $t->tkey = $nextKey;
                    $t->category = $this->category;
                    $t->language = 'en-US';
                    $t->message = $this->messageEN;
                    if (!$t->save()){
                        return false;
                    }
                    if ($currentLanguage == $t->language) {
                        $this->id = $t->id;
                    }
                }
            } else {
                if (!empty($this->messageRU)){
                    $t = self::findOne(['tkey' => $this->tkey, 'language' => 'ru-RU']);
                    if (!isset($t)){
                        $t = new self();
                    }
                    $t->tkey = $this->tkey;
                    $t->category = $this->category;
                    $t->language = 'ru-RU';
                    $t->message = $this->messageRU;
                    if (!$t->save()){
                        return false;
                    }
                }

                if (!empty($this->messageUK)){
                    $t = self::findOne(['tkey' => $this->tkey, 'language' => 'uk-UK']);
                    if (!isset($t)){
                        $t = new self();
                    }
                    $t->tkey = $this->tkey;
                    $t->category = $this->category;
                    $t->language = 'uk-UK';
                    $t->message = $this->messageUK;
                    if (!$t->save()){
                        return false;
                    }
                }

                if (!empty($this->messageEN)){
                    $t = self::findOne(['tkey' => $this->tkey, 'language' => 'en-US']);
                    if (!isset($t)){
                        $t = new self();
                    }
                    $t->tkey = $this->tkey;
                    $t->category = $this->category;
                    $t->language = 'en-US';
                    $t->message = $this->messageEN;
                    if (!$t->save()){
                        return false;
                    }
                }
            }

            $item = new DbMessageSource();
            $t=$item->refreshCache($this->category);

        }
        return $ret;
    }

    public function setLanguages()
    {
        $l = self::find()
            ->where(['tkey' => $this->tkey])
            ->asArray()
            ->all();
        foreach ($l as $item){
            switch ($item['language']){
                case 'ru-RU':
                    $this->messageRU = $item['message'];
                    break;
                case 'uk-UK':
                    $this->messageUK = $item['message'];
                    break;
                case 'en-US':
                    $this->messageEN = $item['message'];
                    break;
            }
        }
        return true;
    }

    public static function getDictionary($category, $language)
    {
        $ret = [];
        $data = self::find()
            ->where(['language' => $language, 'category' => $category])
            ->all();
        if (!empty($data)){
            foreach ($data as $item){
                $tr = $item->translations;
                foreach ($tr as $translation){
                    $ret[$translation->message] =  $item->message;
                }

            }
        }

        return $ret;
    }

    public static function getDataForAutocomplete($language, $category = 'app')
    {
        $data = (new Query())
            ->select('message')
            ->from(self::tableName())
            ->where(['category' => $category, 'language' => $language])
            ->orderBy('message')
            ->indexBy('message')
            ->all();
     //   $result = "['" . implode ( "', '", array_keys($data) ) . "']";
        $result = json_encode(array_keys($data));

        return $result;
    }
}
