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
import br.com.caelum.vraptor.boilerplate.NoCache;

@Controller
@Path("/api/v1/discipline")
public class DisciplineController extends UserControlAbstractController {
	@Inject private DisciplineBS bs;
	
	@Post("/create")
	@Consumes
	@NoCache
	@Permissioned(value = AccessLevels.SYSTEM_ADMIN, permissions = { ManageUsersPermission.class })
	public void create(Discipline discipline, DisciplineCategory category, Institution institution) {
		try {
			if (discipline != null) {
				discipline = this.bs.create(discipline, category, institution);
				this.success(discipline);
			} else {
				this.fail("Campos incompletos");
			}
		} catch (Throwable e) {
			LOGGER.errorf("Erro: %s", e.getMessage());
			this.fail("[Error]: " + e.getMessage());
		}
	}
	
	@Get("/list")
	@NoCache
	public void list() {
		try {
			List<Discipline> disciplineList = this.bs.list();
			this.success(disciplineList, (long) disciplineList.size());
		} catch (Throwable e) {
			LOGGER.errorf("Erro: %s", e.getMessage());
			this.fail("[Error]: " + e.getMessage());
		}
	}
	
	@Get("/category/list")
	@NoCache
	public void listCategory() {
		try {
			List<DisciplineCategory> categoryList = this.bs.listCategory();
			this.success(categoryList, (long) categoryList.size());
		} catch (Throwable e) {
			LOGGER.errorf("Erro: %s", e.getMessage());
			this.fail("[Error]: " + e.getMessage());
		}
	}
	
	@Post("/delete")
	@Consumes
	@Permissioned(value = AccessLevels.SYSTEM_ADMIN, permissions = { ManageUsersPermission.class } )
	public void delete(Discipline discipline) {
		try {
			if (discipline != null) {
				discipline = this.bs.delete(discipline);
				this.success(discipline);
			}
		} catch (Throwable e) {
			LOGGER.errorf("Erro: %s", e.getMessage());
			this.fail("[Error]: " + e.getMessage());
		}
	}
	
	@Post("/subscribe")
	@NoCache
	@Consumes
	public void subscribe(User user, Discipline discipline, String accessKey) {
		try {
			if (user != null && discipline != null) {
				if (!discipline.isClosed()) {
					DisciplineUser disciplineUser = new DisciplineUser();
					disciplineUser = this.bs.subscribe(user, discipline, accessKey);
					this.success(disciplineUser);
				} else {
					this.fail("Terminou o tempo de acesso");
				}
			}
		} catch (Throwable e) {
			LOGGER.errorf("Erro: %s", e.getMessage());
			this.fail("[Error]: " + e.getMessage());
		}
	}
	
	@Post("/unsubscribe")
	@NoCache
	@Consumes
	public void unsubscribe(DisciplineUser disciplineUser, User user, Discipline discipline) {
		try {
			if (disciplineUser != null && user != null && discipline != null) {
				disciplineUser = this.bs.unsubscribe(disciplineUser, user, discipline);
				this.success(disciplineUser);
			}
		} catch (Throwable e) {
			this.fail("[Error]: " + e.getMessage());
		}
	}
	
	@Get("/getDiscipline")
	@NoCache
	public void getDiscipline(Discipline discipline, User user) {
		try {
			LOGGER.infof("Id: %s", discipline.getId());
			if (discipline != null && user != null) {
				LOGGER.info("Entrou");
//				boolean subscribed = this.bs.isSubscribed(discipline, user);
				discipline = this.bs.getDisciplineByShortName(discipline.getId());
				this.success(discipline);
			} else {
				this.fail("User or discipline null");
			}
		} catch (Exception e) {
			LOGGER.error(e);
			this.fail("[Error]: " + e.getMessage());
		}
	}
}
