<?php
	if (!isset($USERLOGIN_INCLUDED)) {
		$USERLOGIN_INCLUDED = true;
		
		class UserLogin {
			public function render() {
				?>
				<div class="fancyBox">
                                    <table class="formTable">
					<form method="POST" action="./controller/UserController.php" class="centering">
                     
                                                        <tr>
                                                                <td><label for="usr_email">E-mail</label>:</td>
                                                                <td><input type="text" name="usr_email" size=30 class="notempty" /></td>
                                                        </tr>
                                                        <tr>
                                                                <td><label for="usr_password">Senha</label>:</td>
                                                                <td><input type="password" name="usr_password" size=30 class="notempty" /></td>
                                                        </tr>
                                                <tr>
                                                    <td colspan="2">
                                                <center>
                                                        <input type="hidden" name="_action" value="login" />
                                                        <input type="submit" value="Entrar" />
      
                                        </form>
                                        <button id="forgotPass" onClick="location.assign('./forgotPass.php');">Esqueci minha senha</button>
                                        </center>
                                    </td>
                                    </tr>
                                     </table>
                                            
                                          
				</div>
				<?php
			}
		}
	}
?>