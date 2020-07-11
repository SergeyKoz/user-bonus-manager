<?php

namespace app\models\forms;

use app\models\User;
use app\services\TransferServiceInterface;
use yii\base\Model;

/**
 * TransferForm is the model for transfer configuration.
 *
 * @property integer $from
 * @property integer $to
 * @property string $description
 * @property double $sum
 */
class TransferForm extends Model
{

    const SCENARIO_REPLENISH = 'replenish';
    const SCENARIO_WITHDRAW = 'withdraw';

    private TransferServiceInterface $transferService;
    private User $fromUser;
    private User $toUser;

    public int $from;
    public int $to;
    public float $sum = 0;
    public string $description;

    public function __construct(User $from, $to, TransferServiceInterface $transferService, $config = [])
    {
        $this->from = $from;
        $this->to = $to;
        $this->transferService = $transferService;

        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['replenish'] = ['from', 'to', 'description', 'sum'];
        $scenarios['withdraw'] = ['from', 'to', 'description', 'sum'];
        return $scenarios;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['from', 'to', 'description'], 'required'],
            ['sum', 'number'],
            ['sum', 'compare', 'compareValue' => 0, 'operator' => '>'],
            ['from', 'validateFromUser'],
            ['to', 'validateToUser']
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validateFromUser($attribute, $params)
    {
        $user = User::findOne(['username' => $this->$attribute]);
        if (isset($user)) {
            $this->fromUser = $user;
        } else {
            $this->addError($attribute, sprintf('User \'%s\' is not found', $this->$attribute));
        }
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validateToUser($attribute, $params)
    {
        $user = User::findOne(['username' => $this->$attribute]);
        if (isset($user)) {
            $this->toUser = $user;
        } else {
            $this->addError($attribute, sprintf('User \'%s\' is not found', $this->$attribute));
        }

        if (!$this->hasErrors()) {
            if ($this->fromUser->id == $this->toUser->id) {
                $this->addError($attribute, sprintf('User \'%s\' can\'n make a transfer to himself', $this->$attribute));
            }
        }
    }

    /**
     * Makes transfer.
     * @return bool whether the operation is successful
     */
    public function transfer()
    {
        try {
            return $this->transferService->transfer(
                $this->fromUser, $this->toUser, $this->sum, $this->description);
        } catch (\Exception $error) {
            $this->addError('sum', sprintf('Transfer error \'%s\'', $error->getMessage()));
        }
        return false;
    }
}
