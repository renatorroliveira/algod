package com.developerkingdom.algod.system.router;

import java.lang.reflect.Method;

import javax.enterprise.inject.spi.CDI;

import br.com.caelum.vraptor.boilerplate.util.GeneralUtils;

public class PrefixedApiRouteResolver implements RuntimeRouteResolver {

	private PrefixedApiRouteConfiguration config;
	
	public PrefixedApiRouteResolver() {
		this.config = CDI.current().select(PrefixedApiRouteConfiguration.class).get();
	}
	
	@Override
	public String[] resolve(Method javaMethod, Class<?> type, String... uris) {
		for (int i = 0; i < uris.length; i++) {
			if (!GeneralUtils.isEmpty(uris[i])) {
				uris[i] = this.config.getRoutesPrefix() + uris[i];
			}
		}
		return uris;
	}

}
