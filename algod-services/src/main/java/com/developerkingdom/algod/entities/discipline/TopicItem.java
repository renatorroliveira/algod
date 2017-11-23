package com.developerkingdom.algod.entities.discipline;

import java.util.Date;

import javax.persistence.Entity;
import javax.persistence.Table;

import br.com.caelum.vraptor.boilerplate.SimpleLogicalDeletableEntity;

@Entity(name = TopicItem.TABLE)
@Table(name = TopicItem.TABLE)
public class TopicItem extends SimpleLogicalDeletableEntity {
	public static final String TABLE = "algod_discipline_topic_items";
	private static final long serialVersionUID = 1L;
	
	private String label;
	
	private String type;
	
	private String content;
	
	private String description;
	
	private Date dateVisibleFrom = new Date();
	
	private Date dateVisibleTo;
	
	private Date dateAvailableFrom = new Date();
	
	private Date dateAvailableTo;
	
	private boolean visible;
	
	private boolean visibleDateEnabled;
	
	private boolean availableDateEnabled;
	
	public String getLabel() {
		return label;
	}

	public void setLabel(String label) {
		this.label = label;
	}

	public String getType() {
		return type;
	}

	public void setType(String type) {
		this.type = type;
	}

	public String getContent() {
		return content;
	}

	public void setContent(String content) {
		this.content = content;
	}

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}

	public Date getDateVisibleFrom() {
		return dateVisibleFrom;
	}

	public void setDateVisibleFrom(Date dateVisibleFrom) {
		this.dateVisibleFrom = dateVisibleFrom;
	}

	public Date getDateVisibleTo() {
		return dateVisibleTo;
	}

	public void setDateVisibleTo(Date dateVisibleTo) {
		this.dateVisibleTo = dateVisibleTo;
	}

	public Date getDateAvailableFrom() {
		return dateAvailableFrom;
	}

	public void setDateAvailableFrom(Date dateAvailableFrom) {
		this.dateAvailableFrom = dateAvailableFrom;
	}

	public Date getDateAvailableTo() {
		return dateAvailableTo;
	}

	public void setDateAvailableTo(Date dateAvailableTo) {
		this.dateAvailableTo = dateAvailableTo;
	}

	public boolean isVisible() {
		return visible;
	}

	public void setVisible(boolean visible) {
		this.visible = visible;
	}

	public boolean isVisibleDateEnabled() {
		return visibleDateEnabled;
	}

	public void setVisibleDateEnabled(boolean visibleDateEnabled) {
		this.visibleDateEnabled = visibleDateEnabled;
	}

	public boolean isAvailableDateEnabled() {
		return availableDateEnabled;
	}

	public void setAvailableDateEnabled(boolean availableDateEnabled) {
		this.availableDateEnabled = availableDateEnabled;
	}
	
}
