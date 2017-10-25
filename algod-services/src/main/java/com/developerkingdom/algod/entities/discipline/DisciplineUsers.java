package com.developerkingdom.algod.entities.discipline;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.FetchType;
import javax.persistence.ManyToOne;
import javax.persistence.Table;

import com.developerkingdom.algod.entities.user.User;

import br.com.caelum.vraptor.boilerplate.SimpleLogicalDeletableEntity;

@Entity(name = DisciplineUsers.TABLE)
@Table(name = DisciplineUsers.TABLE)
public class DisciplineUsers extends SimpleLogicalDeletableEntity {
	public static final String TABLE = "algod_discipline_users";
	private static final long serialVersionUID = 1L;
	
	@Column(nullable=false)
	private int role;
	
	@ManyToOne(fetch=FetchType.EAGER, optional=false)
	private User user;
	
	@ManyToOne(fetch=FetchType.EAGER, optional=false)
	private Discipline discipline;
	
	public int getRole() {
		return role;
	}

	public void setRole(int role) {
		this.role = role;
	}

	public User getUser() {
		return user;
	}

	public void setUser(User user) {
		this.user = user;
	}

	public Discipline getDiscipline() {
		return discipline;
	}

	public void setDiscipline(Discipline discipline) {
		this.discipline = discipline;
	}
	
}
