package com.developerkingdom.algod.entities.user;

import java.util.Date;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Table;
import javax.persistence.Temporal;
import javax.persistence.TemporalType;

import com.developerkingdom.algod.entities.user.authz.AccessLevels;

import br.com.caelum.vraptor.boilerplate.SimpleLogicalDeletableEntity;
import br.com.caelum.vraptor.serialization.SkipSerialization;

/**
 * @author Renato R. R. de Oliveira
 * 
 */
@Entity(name = User.TABLE)
@Table(name = User.TABLE)
public class User extends SimpleLogicalDeletableEntity {
	public static final String TABLE = "algod_users";
	private static final long serialVersionUID = 1L;

	@Column(nullable=false, unique=true, length=255)
	private String email;

	@Column(nullable=true, length=255)
	@SkipSerialization
	private String password;
	
	@Column(nullable=false, length=255)
	private String name;

	@Column(nullable=true, unique=true, length=255)
	private String phone;

	@Column(nullable=true, length=255)
	private String department;
	
	@Column(nullable=true, length=255)
	private String picture;

	@Temporal(TemporalType.TIMESTAMP)
	@Column(nullable=false)
	private Date creation = new Date();
	
	private boolean active = false;
	
	/** Nível de acesso global, independente de tenant. */
	private int accessLevel = AccessLevels.NONE.getLevel();

	public String getEmail() {
		return email;
	}

	public void setEmail(String email) {
		this.email = email;
	}

	public String getPassword() {
		return password;
	}

	public void setPassword(String password) {
		this.password = password;
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getPhone() {
		return phone;
	}

	public void setPhone(String phone) {
		this.phone = phone;
	}

	public String getDepartment() {
		return department;
	}

	public void setDepartment(String department) {
		this.department = department;
	}

	public String getPicture() {
		return picture;
	}

	public void setPicture(String picture) {
		this.picture = picture;
	}

	public Date getCreation() {
		return creation;
	}

	public void setCreation(Date creation) {
		this.creation = creation;
	}

	public boolean isActive() {
		return active;
	}

	public void setActive(boolean active) {
		this.active = active;
	}

	public int getAccessLevel() {
		return accessLevel;
	}

	public void setAccessLevel(int accessLevel) {
		this.accessLevel = accessLevel;
	}
	
}
