package com.developerkingdom.algod.entities.user.authz;

import com.developerkingdom.algod.entities.user.authz.permission.EditMessagesPermission;
import com.developerkingdom.algod.entities.user.authz.permission.ManageUsersPermission;
import com.developerkingdom.algod.entities.user.authz.permission.ViewUsersPermission;
import com.developerkingdom.algod.system.ComponentFactory;

public final class PermissionFactory extends ComponentFactory<Permission> {

	private static final PermissionFactory instance = new PermissionFactory();

	public static PermissionFactory getInstance() {
		return instance;
	}

	private PermissionFactory() {
		// this.register(new SystemAdminPermission());
		this.register(new ManageUsersPermission());
		this.register(new ViewUsersPermission());
		this.register(new EditMessagesPermission());
	}

	public class SystemAdminPermission extends Permission {
		@Override
		public String getDisplayName() {
			return "Administração de Sistema";
		}

		@Override
		public int getRequiredAccessLevel() {
			return AccessLevels.SYSTEM_ADMIN.getLevel();
		}

		@Override
		public String getDescription() {
			return "Adicionar Instituições, Editar Instituições, Remover Instituições, Adicionar Domínios,"
					+ "Editar Domínios, Remover Domínios";
		}
	}
}
