package com.developerkingdom.algod.entities.company;

import java.util.List;

import javax.inject.Inject;

import com.developerkingdom.algod.entities.user.authz.AccessLevels;
import com.developerkingdom.algod.entities.user.authz.Permissioned;
import com.developerkingdom.algod.entities.user.authz.permission.ManageUsersPermission;
import com.developerkingdom.algod.system.UserControlAbstractController;

import br.com.caelum.vraptor.Consumes;
import br.com.caelum.vraptor.Controller;
import br.com.caelum.vraptor.Get;
import br.com.caelum.vraptor.Path;
import br.com.caelum.vraptor.Post;


@Controller
@Path("/api/v1/institution")
public class InstitutionController extends UserControlAbstractController {
	
	@Inject private InstitutionBS bs;
	
	@Post("/register")
	@Consumes
	@Permissioned(value = AccessLevels.SYSTEM_ADMIN, permissions = { ManageUsersPermission.class })
	public void newInstitution(Institution inst) {
		try {
			if (inst != null) {
				inst = this.bs.newInstitution(inst);
				this.success(inst);
			} else {
				LOGGER.error("Você nao tem permissao.");
				this.fail("Você não tem permissão para fazer isso.");
			}
		} catch (Exception e) {
			LOGGER.errorf("Erro: %s", e.getMessage());
			this.fail("Host já cadastrado");
		}
	}
	
	@Get("/listAll")
	@Consumes
	public void listInstitutions() {
		try {
			List<Institution> inst = this.bs.listInstitutions();
			this.success(inst, (long) inst.size());
		} catch (Throwable ex) {
			LOGGER.error("Unexpected error", ex);
			this.fail(ex.getMessage());
		}
	}

	@Post("/delete")
	@Consumes
	@Permissioned(value = AccessLevels.SYSTEM_ADMIN, permissions = { ManageUsersPermission.class })
	public void deleteInstitution(Institution insti) {
		try {
			if (insti != null) {
				insti = this.bs.deleteInstitution(insti.getId());
				this.success(insti);
			}
		} catch (Throwable ex) {
			LOGGER.error("Unexpected error", ex);
			this.fail(ex.getMessage());
		}
	}
}
