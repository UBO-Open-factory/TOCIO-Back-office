<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "capteur".
 *
 * @property int $id
 * @property string $nom
 *
 * @property RelCapteurgrandeur[] $relCapteurgrandeurs
 * @property Grandeur[] $idGrandeurs
 * @property RelModulecapteur[] $relModulecapteurs
 * @property Module[] $idModules
 * @property RelPositionCapteur[] $relPositionCapteurs
 * @property Position[] $idPositions
 */
class Capteur extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'capteur';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nom'], 'required'],
            [['nom'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nom' => 'Nom',
        ];
    }

    /**
     * Gets query for [[RelCapteurgrandeurs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRelCapteurgrandeurs()
    {
        return $this->hasMany(RelCapteurgrandeur::className(), ['idCapteur' => 'id']);
    }

    /**
     * Gets query for [[IdGrandeurs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdGrandeurs()
    {
        return $this->hasMany(Grandeur::className(), ['id' => 'idGrandeur'])->viaTable('rel_capteurgrandeur', ['idCapteur' => 'id']);
    }

    /**
     * Gets query for [[RelModulecapteurs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRelModulecapteurs()
    {
        return $this->hasMany(RelModulecapteur::className(), ['idCapteur' => 'id']);
    }

    /**
     * Gets query for [[IdModules]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdModules()
    {
        return $this->hasMany(Module::className(), ['identifiantReseau' => 'idModule'])->viaTable('rel_modulecapteur', ['idCapteur' => 'id']);
    }

    /**
     * Gets query for [[RelPositionCapteurs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRelPositionCapteurs()
    {
        return $this->hasMany(RelPositionCapteur::className(), ['idCapteur' => 'id']);
    }

    /**
     * Gets query for [[IdPositions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdPositions()
    {
        return $this->hasMany(Position::className(), ['id' => 'idPosition'])->viaTable('rel_positionCapteur', ['idCapteur' => 'id']);
    }
}
