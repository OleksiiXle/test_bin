<!--*************** форма регистрации пользователя администратором -->
<!--*************** рендерится из UserController -> actionSignupByAdmin($invitation = false) -->
<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\adminxx\assets\AdminxxUpdateUserAsset;
use yii\helpers\Json;

AdminxxUpdateUserAsset::register($this);

$_userRoles = Json::htmlEncode($userRoles);
$this->registerJs("
    var _userRoles = {$_userRoles};
",\yii\web\View::POS_HEAD);

if ($model->isNewRecord){
    $update = false;
    $disable = '';
    $this->title = Yii::t('app', 'Регистрация нового пользователя');
} else {
    $update = true;
    $disable = 'disabled';
    $this->title = Yii::t('app', 'Изменение данных пользователя');
}


?>
<style>
    .userCardArea{
        margin-top: 10px;
        margin-bottom: 10px;
        background-color: lightgrey;
        padding: 10px;
    }
    .userDepartmentsArea{
        margin-top: 10px;
        margin-bottom: 10px;
        background-color: aliceblue;
        padding: 10px;

    }
    .selectRoleArea{
        padding: 10px;

    }
    .userRolesArea{
        margin-top: 10px;
        background-color: lemonchiffon;
        padding: 10px;

    }
    .formButtons{
        margin-top: 10px;
        padding: 10px;
    }
</style>

<?php $form = ActiveForm::begin(['id' => 'form-update',]); ?>

<div class="row xHeader">
    <div class="col-md-12 col-lg-12">
        <?= Html::errorSummary($model)?>
        <?= $form->field($model, 'id')->hiddenInput()->label(false);?>
    </div>
</div>

<!--*************************************************************************** ЗОНА ДАННЫХ-->
<div class="row xContent">

    <!--*************************************************************************** КАРТОЧКА ПОЛЬЗОВАТЕЛЯ -->
    <div class="col-md-12 col-lg-12">
        <div class="xCard ">
            <?= $form->field($model, 'last_name')->textInput([
            ]);?>
            <?= $form->field($model, 'first_name')->textInput([
            ]);?>
            <?= $form->field($model, 'middle_name')->textInput([
            ]); ?>
            <?= $form->field($model, 'email'); ?>
            <?= $form->field($model, 'phone'); ?>

            <!--*************************************************************************** РОЛИ ПОЛЬЗОВАТЕЛЯ -->
            <div id="RolesArea">
                <b>Ролі користувача</b>
                <div id="userRoles">
                </div>
                <br>
                <div id="addRoleBtn" align="center">
                    <?= Html::button('Додати роль', [
                        'class' => 'btn btn-primary',
                        'onclick' => '$("#selectRoleArea").show();
                                                      $("#addRoleBtn").hide();
                                      '
                    ])?>
                </div>

            </div>

            <!--***************************************************************** ВЫБОР НОВОЙ РОЛИ ПОЛЬЗОВАТЕЛЯ -->
            <div id="selectRoleArea" class="selectRoleArea" style="display: none;">
                <!--***************************************************************** сЕЛЕКТ для выбора -->
                <div class="row">
                    <div class="col-md-10">
                        <?php
                        echo Html::listBox('defaultRoles', null, $defaultRoles, [
                            'class' => 'form-control',
                        ]);

                        ?>
                    </div>
                    <div class="col-md-1" align="center">
                        <?= Html::a('<span class="glyphicon glyphicon-plus"></span>', false,
                            [
                                'onclick' => 'addUserRole()',
                                'title' => 'Додати роль',
                            ]);
                        ?>
                    </div>
                    <div class="col-md-1" align="center">
                        <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', false,
                            [
                                'onclick' => '$("#rolesArea").show();
                                                      $("#selectRoleArea").hide();
                                                      $("#addRoleBtn").show();
                                                      ',
                                'title' => 'Зховати список',
                            ]);
                        ?>
                    </div>
                </div>
            </div>
            <!--*************************************************************************** УПРАВЛЕНИЕ РОЛЯМИ ПОЛЬЗОВАТЕЛЯ -->
        </div>
    </div>

</div>

<!--*************************************************************************** ЛОГИН ПАРОЛЬ -->
<div class="row xContent">
    <div class="col-md-12 col-lg-6">
        <div class="xCard">
            <div class="row">
                <div class="col-md-12 col-lg-3">
                    <?= $form->field($model, 'username')->textInput([]); ?>
                </div>
                <div class="col-md-12 col-lg-3">
                    <?php
                    if (!$update){
                        echo $form->field($model, 'password');
                    }
                    ?>
                </div>
                <div class="col-md-12 col-lg-3">
                    <?php
                    if (!$update){
                        echo $form->field($model, 'retypePassword')->label('Підтвердження');
                    }
                    ?>
                </div>
                <div class="col-md-12 col-lg-3">
                    <?php
                    if (!$update) {
                        echo $form->field($model, 'invitation', ['inputOptions' =>
                            ['class' => 'form-control']])->dropDownList([0 => 'Нет', 1 => 'Да'],
                            ['options' => [0 => ['Selected' => true]],]);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!--*************************************************************************** КНОПКИ СОХРАНЕНИЯ -->
<div class="row xContent">
    <div class="col-md-12 col-lg-12">
        <div class="row">
            <div class="form-group" align="center">
                <?= Html::button('Зберігти', [
                    'class' => 'btn btn-primary',
                    'name' => 'signup-button',
                    'onclick' => 'saveUser();'
                ]) ?>
                <?= Html::a('Відміна', '/adminxx/user',[
                    'class' => 'btn btn-danger', 'name' => 'reset-button'
                ]);?>
            </div>
        </div>
    </div>
</div>
<div class="row xContent">
    <?= $form->field($model, 'multyFild')->textarea([
        'rows' => '5'
    ])->hiddenInput()->label(false);?>

</div>

<?php ActiveForm::end(); ?>

