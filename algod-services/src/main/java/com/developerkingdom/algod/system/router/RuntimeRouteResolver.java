package com.developerkingdom.algod.system.router;

import java.lang.reflect.Method;

public interface RuntimeRouteResolver {
	String[] resolve(Method javaMethod, Class<?> type, String... uris);
}
