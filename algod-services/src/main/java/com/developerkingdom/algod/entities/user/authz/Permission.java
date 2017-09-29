package com.developerkingdom.algod.entities.user.authz;

import com.developerkingdom.algod.system.IdentifiableComponent;

public abstract class Permission extends IdentifiableComponent {
	
	public abstract String getDescription();

	public int getRequiredAccessLevel() {
		return AccessLevels.AUTHENTICATED.getLevel();
	}

}
