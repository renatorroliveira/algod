<?php
	include "./static/header.php";
?>
<h1>Esqueci minha Senha</h1>

    <form method="POST" action="./controller/RecoveryController.php" class="centering">
        <div class="fancyBox">
                <table class="formTable">
                        <tr>
                                <td><label for="usr_email">E-mail</label>:</td>
                                <td><input type="text" name="usr_email" size=30 class="notempty" /></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <center>    
                                    <input type="hidden" name="_action" value="recovery" />
                                    <input type="submit" value="Recuperar" />
                                </center>
                            </td>
                        </tr>
                </table>
        </div>
    </form>   

<?php
	include "./static/footer.php";
?>