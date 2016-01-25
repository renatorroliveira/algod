<?php
	if (!isset($USEREDIT_INCLUDED)) {
		$USEREDIT_INCLUDED = true;
		
		@include "../model/User.php";
		@include "./model/User.php";
		
		class UserEdit {
			protected $user;
			
			public function __construct($userInstance) {
				$this->user = $userInstance;
			}
			
			public function render() {
				$update = true;
				if (!isset($this->user)) {
					$this->user = new User();
					$update = false;
				}
				?>
				<form method="POST" class="centering"
					action="./controller/UserController.php">
					<table class="formTable">
						<tr>
							<td><label for="usr_name">Nome Completo</label>:</td>
							<td><input name="usr_name" type="text" size=30 class="notempty"
								value="<?php echo @$this->user->get("usr_name"); ?>" /> *</td>
						</tr>
						<tr>
							<td><label for="usr_email">E-mail</label>:</td>
							<td><input name="usr_email" type="text" size=30 class="notempty"
								value="<?php echo @$this->user->get("usr_email"); ?>" /> *</td>
						</tr>
						<tr>
							<td><label for="usr_matricula">Matrícula</label>:</td>
							<td><input name="usr_matricula" type="text" size=30 class="notempty"
								value="<?php echo @$this->user->get("usr_matricula"); ?>" /> *</td>
						</tr>
						<tr>
							<td><label for="usr_password">Senha</label>:</td>
							<td><input name="usr_password" type="password" size=30 class="notempty password" /> *</td>
						</tr>
						<tr>
							<td><label for="passwordconfirm">Confirmar Senha</label>:</td>
							<td><input name="passwordconfirm" type="password" size=30 class="passwordconfirm" /> *</td>
						</tr>
					</table>
					<?php if ($update === true) { ?>
						<input type="hidden" name="usr_id" value="<?php echo @$this->user->get("usr_id"); ?>" />
						<input type="hidden" name="usr_accessLevel" value="<?php echo @$this->user->get("usr_accessLevel"); ?>" />
					<?php } ?>
					<input type="hidden" name="_action" value="save" />
					<input type="submit" value="Salvar" />
					<input type="reset" value="Limpar Campos" />
				</form>
				<?php
			}
		}
	}
?>