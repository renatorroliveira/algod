package com.developerkingdom.algod.entities.discipline.topic;

import java.util.Date;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.FetchType;
import javax.persistence.ManyToOne;
import javax.persistence.Table;
import javax.persistence.Temporal;
import javax.persistence.TemporalType;

import br.com.caelum.vraptor.boilerplate.SimpleLogicalDeletableEntity;

@Entity(name = TopicItem.TABLE)
@Table(name = TopicItem.TABLE)
public class TopicItem extends SimpleLogicalDeletableEntity {
	public static final String TABLE = "algod_discipline_topic_items";
	private static final long serialVersionUID = 1L;
	
	@ManyToOne(fetch=FetchType.EAGER, optional=true)
	private Topic topic;

	private String label;
	
	private String type;
	
	private String content;
	
	private String description;
	
	@Temporal(TemporalType.TIMESTAMP)
	@Column(nullable=false)
	private Date dateVisibleFrom = new Date();
	
	@Temporal(TemporalType.TIMESTAMP)
	@Column(nullable=true)
	private Date dateVisibleTo;
	
	@Temporal(TemporalType.TIMESTAMP)
	@Column(nullable=false)
	private Date dateAvailableFrom = new Date();
	
	@Temporal(TemporalType.TIMESTAMP)
	@Column(nullable=false)
	private Date dateAvailableTo;
	
	private boolean visible = true;
	
	private boolean visibleDateEnabled = true;
	
	private boolean availableDateEnabled = true;
	
	private int contentType;
	
	public int getContentType() {
		return contentType;
	}

	public void setContentType(int contentType) {
		this.contentType = contentType;
	}

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
	
	public Topic getTopic() {
		return topic;
	}

	public void setTopic(Topic topic) {
		this.topic = topic;
	}
	
}
