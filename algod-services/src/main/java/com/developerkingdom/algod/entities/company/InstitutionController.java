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
import br.com.caelum.vraptor.boilerplate.NoCache;


@Controller
@Path("/api/v1/institution")
public class InstitutionController extends UserControlAbstractController {
	
	@Inject private InstitutionBS bs;
	
	@Post("/register")
	@NoCache
	@Consumes
	@Permissioned(value = AccessLevels.SYSTEM_ADMIN, permissions = { ManageUsersPermission.class })
	public void register(Institution inst) {
		try {
			if (inst != null) {
				inst = this.bs.register(inst);
				this.success(inst);
			}
		} catch (Throwable e) {
			LOGGER.errorf("Erro: %s", e.getMessage());
			this.fail("[Error]: " + e.getMessage());
		}
	}
	
	@Get("/list")
	@NoCache
	@Permissioned(value = AccessLevels.SYSTEM_ADMIN, permissions = { ManageUsersPermission.class })
	public void list() {
		try {
			List<Institution> inst = this.bs.listInstitutions();
			this.success(inst, (long) inst.size());
		} catch (Throwable e) {
			LOGGER.errorf("Erro: %s", e.getMessage());
			this.fail("[Error]: " + e.getMessage());
		}
	}

	@Post("/delete")
	@NoCache
	@Consumes
	@Permissioned(value = AccessLevels.SYSTEM_ADMIN, permissions = { ManageUsersPermission.class })
	public void delete(Institution institution) {
		try {
			if (institution != null) {
				institution = this.bs.delete(institution);
				this.success(institution);
			}
		} catch (Throwable e) {
			LOGGER.errorf("Erro: %s", e.getMessage());
			this.fail("[Error]: " + e.getMessage());
		}
	}
}
