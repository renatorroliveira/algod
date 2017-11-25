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
	
	@Get("/{id}/subscription")
	@NoCache
	@Permissioned
	public void retrieveSubscription(Long id) {
		try {
			if (id != null) {
				Discipline discipline = this.bs.exists(id, Discipline.class);
				if (discipline == null)
					this.result.notFound();
				else {
					this.success(this.bs.isSubscribed(discipline, this.userSession.getUser()));
				}
			} else {
				this.fail("User or discipline null");
			}
		} catch (Exception e) {
			LOGGER.error(e);
			this.fail("[Error]: " + e.getMessage());
		}
	}
	
	@Post("/{id}/subscribe")
	@NoCache
	@Consumes
	@Permissioned
	public void subscribe(Long id, String accessKey) {
		try {
			if (id != null) {
				Discipline discipline = this.bs.exists(id, Discipline.class);
				if (discipline == null)
					this.result.notFound();
				else {
					DisciplineUser disciplineUser = this.bs.subscribe(this.userSession.getUser(), discipline, accessKey);
					if (disciplineUser == null)
						this.fail("Senha inválida");
					else
						this.success(disciplineUser);
				}
			} else {
				this.fail("User or discipline null");
			}
		} catch (Exception e) {
			LOGGER.error(e);
			this.fail("[Error]: " + e.getMessage());
		}
	}
	
	@Post("/unsubscribe")
	@NoCache
	@Consumes
	public void unsubscribe(Discipline discipline) {
		try {
			if (discipline != null) {
				DisciplineUser disciplineUser = this.bs.unsubscribe(discipline, this.userSession.getUser());
				this.success(disciplineUser);
			}
		} catch (Throwable e) {
			this.fail("[Error]: " + e.getMessage());
		}
	}
	
	@Get("/{id}")
	@NoCache
	@Permissioned
	public void retrieve(Long id) {
		try {
			if (id != null) {
				Discipline discipline = this.bs.exists(id, Discipline.class);
				if (discipline == null)
					this.result.notFound();
				else
					LOGGER.info("ENTROU GET DISCIPLINE");
					this.success(discipline);
			} else {
				this.fail("User or discipline null");
			}
		} catch (Exception e) {
			LOGGER.error(e);
			this.fail("[Error]: " + e.getMessage());
		}
	}
	
	@Get("/search/{query}")
	public void searchDiscipline(String query) {
		try {
			if (query != null) {
				Discipline discipline = this.bs.search(query);
				if (discipline != null) {
					this.success(discipline);
				} else {
					this.fail("Disciplina não encontrada");
				}
			}
		} catch (Throwable e) {
			this.fail(e.getMessage());
		}
	}
}
