<?php
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;

class ArticlesTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }

    public function beforeSave($event, $entity, $options)
    {
        if ($entity->isNew() && !$entity->slug) {
            $sluggedTitle = Text::slug($entity->title);
            // スラグをスキーマで定義されている最大長に調整
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }

    // Validationの詳細は以下のページにある。
    // https://book.cakephp.org/3.0/ja/core-libraries/validation.html
    public function validationDefault(Validator $validator) : Validator
    {
        $validator
            ->allowEmptyString('title', false)
            ->minLength('title', 10)
            ->maxLength('title', 255)

            ->allowEmptyString('body', false)
            ->minLength('body', 10);

        return $validator;
    }
}