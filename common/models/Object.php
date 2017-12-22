<?php

namespace common\models;

use Yii;

class Object extends \yii\base\Object
{
	public $id;
	public $name;

	public function getId()
	{
		return $this->id;
	}

	public function setId($value)
	{
		$this->id = $value;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($value)
	{
		$this->name = $value;
	}
}