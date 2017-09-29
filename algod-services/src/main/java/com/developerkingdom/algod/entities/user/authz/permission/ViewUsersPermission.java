package com.developerkingdom.algod.entities.user.authz.permission;

import com.developerkingdom.algod.entities.user.authz.AccessLevels;
import com.developerkingdom.algod.entities.user.authz.Permission;

public class ViewUsersPermission extends Permission {

	@Override
	public String getDisplayName() {
		return "Visualizar Usuários";
	}

	@Override
	public int getRequiredAccessLevel() {
		return AccessLevels.MANAGER.getLevel();
	}

	@Override
	public String getDescription() {
		return "Listar usuários, Consultar informações de um usuário, Enviar mensagem para um usuário";
	}
}
