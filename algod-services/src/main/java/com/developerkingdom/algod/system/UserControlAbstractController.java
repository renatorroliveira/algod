package com.developerkingdom.algod.system;

import javax.inject.Inject;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.developerkingdom.algod.entities.user.auth.UserSession;

import br.com.caelum.vraptor.boilerplate.AbstractController;

/**
 * Especialização da classe abstrata para controladores com o objetivo de fornecer um prefixo
 * para os end points REST e injetar o objeto de sessão de usuário automaticamente nos
 * controladores do sistema. Todos os controladores devem estender dessa classe.
 * 
 * @author Renato R. R. de Oliveira
 *
 */
public abstract class UserControlAbstractController extends AbstractController {

	@Inject protected UserSession userSession;
	@Inject protected HttpServletRequest request;
	@Inject protected HttpServletResponse response;
}
