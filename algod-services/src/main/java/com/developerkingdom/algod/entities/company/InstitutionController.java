package com.developerkingdom.algod.entities.company;

import javax.inject.Inject;

import com.developerkingdom.algod.entities.user.auth.UserSession;
import com.developerkingdom.algod.entities.user.authz.AccessLevels;
import com.developerkingdom.algod.entities.user.authz.Permissioned;
import com.developerkingdom.algod.entities.user.authz.permission.ManageUsersPermission;
import com.developerkingdom.algod.system.UserControlAbstractController;

import br.com.caelum.vraptor.Consumes;
import br.com.caelum.vraptor.Controller;
import br.com.caelum.vraptor.Path;
import br.com.caelum.vraptor.Post;


@Controller
@Path("/api/v1/institution")
public class InstitutionController extends UserControlAbstractController {
	
	@Inject private InstitutionBS bs;
	@Inject private UserSession userSession;
	
	@Post("/register")
	@Consumes
	@Permissioned(value = AccessLevels.SYSTEM_ADMIN, permissions = { ManageUsersPermission.class })
	public void newInstitution(Institution inst) {
		try {
			if (inst != null) {
				if (userSession.getAccessLevel() == 100) {
					inst = this.bs.newInstitution(inst);
					this.success(inst);
				} else {
					LOGGER.error("Você nao tem permissao.");
					this.fail("Você não tem permissão para fazer isso.");
				}
			}
		} catch (Exception e) {
			LOGGER.errorf("Erro: %s", e.getMessage());
			this.fail("Host já cadastrado");
		}
	}
}
