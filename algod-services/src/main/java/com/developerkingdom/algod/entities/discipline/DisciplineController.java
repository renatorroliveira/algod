package com.developerkingdom.algod.entities.discipline;

import java.util.List;

import javax.inject.Inject;

import com.developerkingdom.algod.entities.company.Institution;
import com.developerkingdom.algod.entities.user.User;
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
@Path("/api/v1/discipline")
public class DisciplineController extends UserControlAbstractController {
	@Inject private DisciplineBS bs;
	
	@Post("/create")
	@Consumes
	@Permissioned(value = AccessLevels.SYSTEM_ADMIN, permissions = { ManageUsersPermission.class })
	public void create(Discipline discipline, DisciplineCategory category, Institution institution) {
		try {
			if (discipline != null) {
				discipline.setCategory(category);
				discipline.setInstitution(institution);
				discipline = this.bs.create(discipline);
				this.success(discipline);
			} else {
				this.fail("Campos incompletos");
			}
		} catch (Exception e) {
			LOGGER.errorf("Erro: %s", e.getMessage());
			this.fail(e.getMessage());
		}
	}
	
	@Get("/list")
	@Consumes
	@Permissioned(value = AccessLevels.SYSTEM_ADMIN, permissions = { ManageUsersPermission.class })
	public void list() {
		try {
			List<Discipline> disciplineList = this.bs.list();
			this.success(disciplineList, (long) disciplineList.size());
		} catch (Exception e) {
			LOGGER.errorf("Erro: %s", e.getMessage());
			this.fail("Erro inesperado");
		}
	}
	
	@Get("/category/list")
	@Consumes
	@Permissioned(value = AccessLevels.SYSTEM_ADMIN, permissions = { ManageUsersPermission.class })
	public void listCategory() {
		try {
			List<DisciplineCategory> categoryList = this.bs.listCategory();
			this.success(categoryList, (long) categoryList.size());
		} catch (Exception e) {
			LOGGER.errorf("Erro: %s", e.getMessage());
			this.fail("Erro inesperado");
		}
	}
	
	@Post("/delete")
	@Consumes
	@Permissioned(value = AccessLevels.SYSTEM_ADMIN, permissions = { ManageUsersPermission.class } )
	public void delete(Discipline discipline) {
		try {
			if (discipline != null) {
				discipline = this.bs.delete(discipline.getId());
				this.success(discipline);
			} else {
				this.fail("Campos incompletos");
			}
		} catch (Exception e) {
			LOGGER.errorf("Erro: %s", e.getMessage());
			this.fail("Erro inesperado");
		}
	}
	
	@Post("/discipline/subscribe")
	@Consumes
	public void subscribe(User user, Discipline discipline, String keyPass) {
		try {
			if (user != null && discipline != null) {
				DisciplineUsers disciplineUsers = new DisciplineUsers();
				disciplineUsers = this.bs.subscribe(disciplineUsers, user, discipline, keyPass);
				this.success(disciplineUsers);
			}
		} catch (Exception e) {
			this.fail(e.getMessage());
		}
	}
	
	@Post("/discipline/unsubscribe")
	@Consumes
	public void unsubscribe(DisciplineUsers disciplineUsers, User user, Discipline discipline) {
		try {
			if (disciplineUsers != null && user != null && discipline != null) {
				disciplineUsers = this.bs.unsubscribe(disciplineUsers, user, discipline);
				this.success(disciplineUsers);
			}
		} catch (Exception e) {
			this.fail(e.getMessage());
		}
	}
}
