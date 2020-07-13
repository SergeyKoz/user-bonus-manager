<?php

namespace app\services;

use app\models\forms\TransferForm;
use app\models\User;
use Yii;
use yii\web\NotFoundHttpException;

class TransferFormFactory
{
    /**
     * @var TransferServiceInterface Money transfer service
     */
    private TransferServiceInterface $transferService;

    /**
     * TransferFormFactory constructor.
     * @param TransferServiceInterface $transferService
     */
    public function __construct(TransferServiceInterface $transferService)
    {
        $this->transferService = $transferService;
    }

    /**
     * Create form object
     * @param int $from
     * @param int $to
     * @return TransferForm
     * @throws NotFoundHttpException
     */
    public function Create(int $from, int $to): TransferForm
    {
        return new TransferForm(
            $this->findModel($from)->username,
            $this->findModel($to)->username,
            $this->transferService);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}