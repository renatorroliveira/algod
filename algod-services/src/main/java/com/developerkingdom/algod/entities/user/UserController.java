package com.developerkingdom.algod.entities.user;

import java.util.List;

import javax.inject.Inject;

import com.developerkingdom.algod.entities.Current;
import com.developerkingdom.algod.entities.bean.SessionInfo;
import com.developerkingdom.algod.entities.company.InstitutionDomain;
import com.developerkingdom.algod.entities.user.auth.UserAccessToken;
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
import br.com.caelum.vraptor.boilerplate.bean.PaginatedList;
import br.com.caelum.vraptor.boilerplate.util.GeneralUtils;

/**
 * @author Renato R. R. de Oliveira
 */
@Controller
@Path("/api/v1/user")
public class UserController extends UserControlAbstractController {

	@Inject private UserBS bs;
	@Inject @Current private InstitutionDomain currentDomain;

	@Post("/register")
	@Consumes
	@NoCache
	public void register(User user, String deviceId) {
		try {
			if (this.bs.existsByCellphone(user.getPhone()) == null) {
				if (this.bs.existsByEmail(user.getEmail()) == null) {
					if (user != null) {
						user.setDeleted(false);
						user.setActive(true);
						user = this.bs.register(user);
						this.success(user);
					}
				} else {
					this.fail("E-mail já cadastrado.");
				}
			} else {
				this.fail("Telefone já cadastrado.");
			}
		} catch (Throwable e) {
			LOGGER.error("Erro no registro.", e);
			this.fail("Ocorreu um erro inesperado: " + e.getMessage());
		}
	}

	@Post("/login")
	@Consumes
	@NoCache
	public void login(String email, String password, String deviceId) {
		try {
			LOGGER.warnf("Tried with '%s' and '%s', deviceId: %s.", email, password, deviceId);
			UserAccessToken token = this.bs.authenticate(email, password, deviceId);
			if (token != null) {
				this.userSession.login(token);
				SessionInfo info = new SessionInfo(this.userSession);
				info.setTenant(this.currentDomain);
				this.success(info);
			} else {
				this.fail("E-mail e/ou senha inválido(s).");
			}
		} catch (Throwable e) {
			LOGGER.error("Erro no login.", e);
			this.fail("Ocorreu um erro inesperado: " + e.getMessage());
		}
	}

	@Post("/logout")
	@NoCache
	public void logoutAjax() {
		try {
			this.userSession.logout();
			this.success();
		} catch (Throwable e) {
			LOGGER.error("Unexpected runtime error", e);
			this.fail("Ocorreu um erro inesperado: " + e.getMessage());
		}
	}
	
	@Get("/logged")
	@NoCache
	public void fetchSession() {
		try {
			if (!this.userSession.isLogged()) {
				String auth = this.request.getHeader("Authorization");
				if (GeneralUtils.isEmpty(auth)) {
					this.fail("Not logged in.");
					this.response.addHeader("Access-Control-Allow-Origin", "http://localhost:*");
					return;
				}
				UserAccessToken token = this.bs.exists(auth, UserAccessToken.class);
				if (token == null) {
					this.fail("Invalid token.");
					return;
				}
				this.userSession.login(token);
			}
			final SessionInfo info = new SessionInfo(this.userSession);
			info.setTenant(this.currentDomain);
			this.success(info);
		} catch (Throwable e) {
			LOGGER.error("Unexpected runtime error", e);
			this.fail("Ocorreu um erro inesperado: " + e.getMessage());
		}
	}
	
	
	@Post("/recover-password")
	@Consumes
	@NoCache
	public void requestRecover(String email) {
		try {
			User user = this.bs.existsByEmail(email);
			if (user == null) {
				this.fail("Este e-mail não está cadastrado no sistema.");
			} else {
				boolean userInactive = this.bs.userIsDeleted(email);
				if (userInactive) {
					this.fail("Este usuário foi bloqueado. Entre em contato com o administrador do sistema.");
				} else {
					UserRecoverRequest req = this.bs.requestRecover(user);
					String url = "http://localhost:8000/" + "#/auth/forgot-password/reset/" + req.getToken();
					LOGGER.infof("URL de recuperação: %s", url);
					this.success(req.getToken());
				}
			}
		} catch (Throwable ex) {
			LOGGER.errorf(ex, "Unexpected error occurred.");
			this.fail(ex.getMessage());
		}
	}

	@Post("/recover-password/{token}")
	@NoCache
	public void canReset(String token) {
		try {
			UserRecoverRequest req = this.bs.retrieveRecoverRequest(token);
			if (req == null) {
				this.fail("Token de recuperação inválida ou expirada.");
			} else {
				this.success(true);
			}
		} catch (Throwable ex) {
			LOGGER.errorf(ex, "Unexpected error occurred.");
			this.fail(ex.getMessage());
		}
	}

	@Post("/reset-password/{token}")
	@Consumes
	@NoCache
	public void resetUserPassword(String password, String token) {
		try {
			if (GeneralUtils.isEmpty(password)) {
				this.fail("A senha não pode ser vazia.");
				LOGGER.info("Senha vazia");
			} else if (this.bs.resetPassword(password, token)) {
				this.success();
			} else {
				LOGGER.info("Token Expirou ou é invalido");
				this.fail("Token de recuperação inválida ou expirada.");
			}
		} catch (Throwable ex) {
			LOGGER.errorf(ex, "Unexpected error occurred.");
			this.fail(ex.getMessage());
		}
	}
	
	@Post("/picture")
	@Consumes
	@NoCache
	public void updatePictureUser(User user, String url) {
		try {
			User existent = this.bs.exists(this.userSession.getUser().getId(), User.class);
			if (url == null) {
				this.fail("Imagem Inválida.");
			} else {
				existent.setPicture(url);
				this.bs.persist(existent);
				this.success(existent);
			}

		} catch (Throwable e) {
			LOGGER.error("Erro no login.", e);
			this.fail("Ocorreu um erro inesperado: " + e.getMessage());
		}
	}
	
	@Get("/list")
	@NoCache
	@Permissioned(value = AccessLevels.SYSTEM_ADMIN, permissions = { ManageUsersPermission.class })
	public void list() {
		try {
			List<User> userList = this.bs.listUsers();
			this.success(userList, (long) userList.size());
		} catch (Throwable e) {
			this.fail(e.getMessage());
		}
	}
	
	@Get("/paginatedList/{terms}")
	@NoCache
	public void paginatedList(String terms) {
		try {
			PaginatedList<User> list = this.bs.paginatedList(terms);
			this.success(list);
		} catch (Throwable e) {
			this.fail(e.getMessage());
		}
	}
	
	@Get("/profile/{nickname}")
	@NoCache
	public void getUser(String nickname) {
		try {
			User user = this.bs.existsByNickname(nickname);
			if (user == null)
				this.result.notFound();
			else {
				user = this.bs.getUserByNick(nickname);
				this.success(user);
			}
		} catch (Throwable e) {
			this.fail(e.getMessage());
		}
	}
	
//	@Post("/api/user")
//	@Consumes
//	@NoCache
//	@Permissioned(value = AccessLevels.COMPANY_ADMIN, permissions = { ManageUsersPermission.class })
//	public void save(@NotEmpty String name, @NotEmpty String email, Integer accessLevel) {
//		try {
//			/* int actualAccess = this.bs.retrieveAccessLevel(this.userSession.getUser());
//				if (accessLevel > actualAccess) {
//					this.forbidden();
//					return;
//				}
//			*/
//			User user = this.bs.inviteUser(name, email, accessLevel);
//
//			this.success(user);
//		} catch (Throwable e) {
//			LOGGER.error("Unexpected runtime error", e);
//			this.fail("E-mail do usuário já foi cadastrado!");
//		}
//	}
//	
//	/**
//	 * Cadastra um usuário sem enviar convite
//	 * 
//	 * @param name
//	 *            Nome do usuário.
//	 * @param email
//	 *            Email do usuário.
//	 * @param password
//	 * 			  Senha do usuário.
//	 * @param accessLevel
//	 *            Nível de acesso do usuário.
//	 * @return User Usuário cadastrado
//	 */
//	@Post("/api/user/register")
//	@Consumes
//	@NoCache
//	@Permissioned(value = AccessLevels.COMPANY_ADMIN, permissions = { ManageUsersPermission.class })
//	public void register(@NotEmpty String name, @NotEmpty String email, @NotEmpty String password, Integer accessLevel) {
//		try {
//			User user = this.bs.existsByEmail(email);
//			if (user == null)
//				user = new User();
//			user.setName(name);
//			user.setEmail(email);
//			user.setPassword(CryptManager.passwordHash(password));
//			user.setDeleted(false);
//			user.setActive(true);
//			this.bs.persist(user);
//			
//			CompanyUser companyUser = new CompanyUser();
//			companyUser.setCompany(this.domain.getCompany());
//			companyUser.setUser(user);
//			CompanyUser existent = this.bs.exists(companyUser, CompanyUser.class);
//			if (existent == null) {
//				companyUser.setAccessLevel(accessLevel);
//				this.bs.persist(companyUser);
//			}
//
//			this.success(user);
//		} catch (Throwable e) {
//			LOGGER.error("Unexpected runtime error", e);
//			this.fail("E-mail do usuário já foi cadastrado!");
//		}
//	}
//
//	/**
//	 * Atualizar um determinado campo do usuário.
//	 * 
//	 * @param id
//	 *            Id do usuário que se deseja atualizar o campo.
//	 * @param field
//	 *            Campo que se deseja atualizar.
//	 * @param value
//	 *            Novo valor do campo.
//	 * 
//	 * @return void
//	 */
//	@Post("/api/user/{id}/update/field")
//	@Consumes
//	@NoCache
//	@Permissioned(value = AccessLevels.COMPANY_ADMIN, permissions = { ManageUsersPermission.class })
//	public void updateUserField(@NotNull Long id, String field, String value) {
//		try {
//			User user = this.bs.exists(id, User.class);
//			if (user == null) {
//				this.result.notFound();
//				return;
//			}
//
//			if ("name".equals(field)) {
//				user.setName(value);
//			} else if ("department".equals(field)) {
//				user.setDepartment(value);
//			} else if ("phone".equals(field)) {
//				user.setPhone(value);
//			} else if ("cellphone".equals(field)) {
//				user.setCellphone(value);
//			} else if ("birthdate".equals(field)) {
//				user.setBirthdate(GeneralUtils.parseDate(value));
//			} else if ("accessLevel".equals(field)) {
//				CompanyUser companyUser = this.bs.retrieveCompanyUser(user, this.domain.getCompany());
//				if (companyUser == null) {
//					this.result.notFound();
//					return;
//				}
//				companyUser.setAccessLevel(Integer.parseInt(value));
//				this.bs.persist(companyUser);
//			} else {
//				this.fail("Campo inválido: " + field);
//			}
//			this.bs.persist(user);
//			this.success();
//		} catch (Throwable e) {
//			LOGGER.error("Unexpected runtime error", e);
//			this.fail("Ocorreu um erro inesperado: " + e.getMessage());
//		}
//	}
//
//	/**
//	 * Reenviar convite para usuário acessar o sistema.
//	 * 
//	 * @param id
//	 *            Id do usuário.
//	 * @return Mensagem de feedback.
//	 */
//	@Post("/api/user/{id}/reinvite")
//	@Consumes
//	@NoCache
//	@Permissioned(value = AccessLevels.COMPANY_ADMIN, permissions = { ManageUsersPermission.class })
//	public void resendInvitation(@NotNull Long id) {
//		try {
//			User user = this.bs.exists(id, User.class);
//			if (user == null) {
//				this.result.notFound();
//				return;
//			}
//			this.bs.sendInvitationEmail(user);
//			this.success("Convite reenviado com sucesso.");
//		} catch (Throwable e) {
//			LOGGER.error("Unexpected runtime error", e);
//			this.fail("E-mail do usuário já foi cadastrado!");
//		}
//	}
//
//	/**
//	 * Atualizar a imagem de perfil do usuário.
//	 * 
//	 * @param user
//	 *            Usuário que terá sua imagem de perfil atualizada.
//	 * @param url
//	 *            Url que contém a foto de perfil do usuário.
//	 * @return User Usuário com a foto de perfil atualizada.
//	 */

//
//	/**
//	 * Usuário do tipo administrador realiza update na foto de perfil de um
//	 * usuário.
//	 * 
//	 * @param user
//	 *            Usuário que terá a foto de perfil atualizada.
//	 * @param url
//	 *            Url que contém a foto do perfil do usuário.
//	 */
//	@Post("/api/user/pictureEditUser")
//	@Consumes
//	@NoCache
//	@Permissioned
//	public void updatePictureEditUser(User user, String url) {
//		try {
//			User existent = this.bs.existsByUser(user.getId());
//			if (url == null) {
//				this.fail("Imagem Inválida.");
//			} else {
//				existent.setPicture(url);
//				this.bs.persist(existent);
//				this.success(existent);
//			}
//
//		} catch (Throwable e) {
//			LOGGER.error("Erro no login.", e);
//			this.fail("Ocorreu um erro inesperado: " + e.getMessage());
//		}
//	}
//
//


}
