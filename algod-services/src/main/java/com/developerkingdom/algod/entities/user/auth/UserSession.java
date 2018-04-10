package com.developerkingdom.algod.entities.user.auth;

import java.io.Serializable;
import java.util.List;

import javax.enterprise.context.SessionScoped;

import com.developerkingdom.algod.entities.user.User;

/**
 * @author Rodrigo Freitas
 *
 */
@SessionScoped
public class UserSession implements Serializable {
	private static final long serialVersionUID = 2L;
	
	private UserAccessToken accessToken;
	private List<String> permissions;
	private int accessLevel = 0;
	
	public void login(UserAccessToken token) {
		this.logout();
		this.accessToken = token;
		
//		UserBS bs = CDI.current().select(UserBS.class).get();
		// TODO Recuperar as permissões de fato.
//		List<UserPermission> perms = null;
//		if (GeneralUtils.isEmpty(perms)) {
//			this.permissions = new ArrayList<String>();
//		} else {
//			this.permissions = new ArrayList<String>(perms.size());
//			for (UserPermission perm : perms) {
//				this.permissions.add(perm.getPermission());
//			}
//		}
		
		this.accessLevel = token.getUser().getAccessLevel();
	}
	
	public void logout() {
		if (this.accessToken != null) {
			this.accessToken = null;
			this.permissions = null;
			this.accessLevel = 0;
		}
	}
	
	public boolean isLogged() {
		return (this.accessToken != null);
	}

	public int getAccessLevel() {
		return this.accessLevel;
	}

	public List<String> getPermissions() {
		return this.permissions;
	}

	public User getUser() {
		return this.accessToken.getUser();
	}
	
	public UserAccessToken getToken() {
		return this.accessToken;
	}
	
}
