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
		} catch (Throwable e) {
			LOGGER.errorf("Erro: %s", e.getMessage());
			this.fail(e.getMessage());
		}
	}
	
	@Get("/list")
	public void list() {
		try {
			List<Discipline> disciplineList = this.bs.list();
			this.success(disciplineList, (long) disciplineList.size());
		} catch (Throwable e) {
			LOGGER.errorf("Erro: %s", e.getMessage());
			this.fail("Erro inesperado: " + e.getMessage());
		}
	}
	
	@Get("/category/list")
	public void listCategory() {
		try {
			List<DisciplineCategory> categoryList = this.bs.listCategory();
			this.success(categoryList, (long) categoryList.size());
		} catch (Throwable e) {
			LOGGER.errorf("Erro: %s", e.getMessage());
			this.fail("[Error] listCategory(): " + e.getMessage());
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
		} catch (Throwable e) {
			LOGGER.errorf("Erro: %s", e.getMessage());
			this.fail("Erro inesperado");
		}
	}
	
	@Post("/subscribe")
	@Consumes
	public void subscribe(User user, Discipline discipline, String accessKey) {
		try {
			if (user != null && discipline != null) {
				if (!discipline.isClosed()) {
					DisciplineUser disciplineUser = new DisciplineUser();
					disciplineUser = this.bs.subscribe(disciplineUser, user, discipline, accessKey);
					this.success(disciplineUser);
				}
			}
		} catch (Throwable e) {
			this.fail(e.getMessage());
		}
	}
	
	@Post("/unsubscribe")
	@Consumes
	public void unsubscribe(DisciplineUser disciplineUser, User user, Discipline discipline) {
		try {
			if (disciplineUser != null && user != null && discipline != null) {
				disciplineUser = this.bs.unsubscribe(disciplineUser, user, discipline);
				this.success(disciplineUser);
			}
		} catch (Exception e) {
			this.fail(e.getMessage());
		}
	}
}
