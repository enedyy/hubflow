import React, { useEffect, useRef, useState } from "react";
import axios from "axios";
import { Link, useNavigate } from "react-router-dom";
import { authLogin } from "../context/authLogin";
import validator from "validator";
import { Button, buttonVariants } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import { Input } from "@/components/ui/input";
import CircularProgress from "@mui/material/CircularProgress";
import Erro from "@/components/componentes/erro";
import { motion } from "framer-motion";
import logo from "../assets/logoHubflow.png";
import Tipo_Usuario from "./cadastro/tipoUsuario";
import Empresa from "./empresa";
import Cliente from "./cadastro/cliente";

const Login = () => {
  const [email, setEmail] = useState("");
  const [senha, setSenha] = useState("");
  const [erro, setErro] = useState("");
  const [btnLoading_Submit, set_btnLoading_Submit] = useState(false);
  const navigate = useNavigate();
  const { login, user } = authLogin();

  const inputEmail = useRef(null);
  useEffect(() => {
    if (inputEmail.current) {
      inputEmail.current.focus();
    }
  }, []);

  async function EnviarFormulario(event) {
    event.preventDefault();

    // Função para validar email usando regex
    const isValidEmail = (email) => {
      const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return regex.test(email);
    };

    if (!isValidEmail(email)) {
      setErro("insira um email válido");
      return;
    }

    setErro("");
    set_btnLoading_Submit(true);

    const timeout = 15000;
    const timeoutPromise = new Promise((_, reject) =>
      setTimeout(() => reject(new Error("Tempo limite excedido")), timeout)
    );

    try {
      const response = await Promise.race([
        axios.get(
          "http://192.168.1.216/hub/hubflow/hubflow/api_vazia/autenticacao",
          {
            email: email,
            senha: senha,
          },
          {
            withCredentials: true,
            headers: {
              "Content-Type": "application/json",
            },
          }
        ),
        timeoutPromise,
      ]);
      login({
        nome: response.data.nome,
        email: response.data.email,
        tipoUser: response.data.tipoUser,
        id: response.data.id,
      });
    } catch (error) {
      if (error.response) {
        // Erro com resposta do servidor
        if (error.response.status === 401) {
          setErro("Email ou senha inválidos");
        } else {
          setErro(
            `Erro do servidor: ${
              error.response.data.message || "Erro desconhecido"
            }`
          );
        }
      } else if (error.message === "Tempo limite excedido") {
        setErro("O servidor demorou muito para responder. Tente novamente.");
      } else {
        setErro("Erro ao conectar com o servidor. Verifique sua conexão.");
      }
    } finally {
      set_btnLoading_Submit(false);
    }
  }

  return (
    <div className="w-full h-screen bg-colorBack flex justify-center items-center p-3 overflow-hidden">
      {/* IDENTIFICACAO DA PAGINA */}
      <div className="bg-colorPrimary w-2/4 max-sm:hidden h-full rounded-l-md relative flex justify-center items-center">
        <Link
          to={"/"}
          className="absolute top-3 left-2 text-xl font-bold text-blue-50"
        >
          <div className="w-28 h-24">
            <img
              src={logo}
              alt="Hubflow"
              className="w-full h-full object-cover"
            />
          </div>
        </Link>

        <div className="w-3/4 gap-2 flex flex-col">
          <Label size="titleLg" className="text-slate-100 capitalize">
            Seja bem-vindo!
          </Label>
          <Label size="large" className="text-colorBack">
            Estamos felizes em recebê-lo novamente em nosso sistema!! Entre com
            o seu login e senha, e encontre os melhores estabelecimentos para
            agendar ainda hoje o seu serviço
          </Label>
        </div>
      </div>

      {/* FORMULARIO LOGIN */}
      <motion.form
        initial={{ opacity: 0, x: 100 }}
        animate={{ opacity: 1, x: 0 }}
        exit={{ opacity: 0, x: -100 }}
        transition={{ duration: 0.3 }}
        method="post"
        onSubmit={EnviarFormulario}
        className="w-2/4 px-4 h-full relative flex flex-col justify-center items-center gap-4 max-sm:w-full"
      >
        <div className="flex flex-col w-3/4 gap-3">
          <Link to="/" className="sm:hidden mb-5 flex items-center gap-1">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              strokeWidth={1.5}
              stroke="currentColor"
              className="size-5"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18"
              />
            </svg>
            <Label size="large">Voltar</Label>
          </Link>
          <Label size="subtitle">Login</Label>
          {/* MENSAGEM DE ERRO PARA EMAIL E SENHA */}
          {/* COMPONENTE MENSAGEM DE ERRO */}
          <Erro props={erro} />
          {/* CAMPO EMAIL */}
          <>
            <Label size="medium">Email</Label>
            <div className="relative">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="currentColor"
                className="size-5 fill-colorPrimary absolute inset-y-2 left-1.5"
              >
                <path
                  fillRule="evenodd"
                  d="M17.834 6.166a8.25 8.25 0 1 0 0 11.668.75.75 0 0 1 1.06 1.06c-3.807 3.808-9.98 3.808-13.788 0-3.808-3.807-3.808-9.98 0-13.788 3.807-3.808 9.98-3.808 13.788 0A9.722 9.722 0 0 1 21.75 12c0 .975-.296 1.887-.809 2.571-.514.685-1.28 1.179-2.191 1.179-.904 0-1.666-.487-2.18-1.164a5.25 5.25 0 1 1-.82-6.26V8.25a.75.75 0 0 1 1.5 0V12c0 .682.208 1.27.509 1.671.3.401.659.579.991.579.332 0 .69-.178.991-.579.3-.4.509-.99.509-1.671a8.222 8.222 0 0 0-2.416-5.834ZM15.75 12a3.75 3.75 0 1 0-7.5 0 3.75 3.75 0 0 0 7.5 0Z"
                  clipRule="evenodd"
                />
              </svg>

              <Input
                variant="inputIcon"
                type="text"
                value={email}
                ref={inputEmail}
                placeholder="user@gmail.com"
                onChange={(event) => setEmail(event.target.value)}
              />
            </div>
          </>
          <>
            <Label size="medium">Senha</Label>
            <div className="relative">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                strokeWidth={1.5}
                stroke="currentColor"
                className="size-5 stroke-colorPrimary absolute inset-y-2 left-1.5"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"
                />
              </svg>
              <Input
                variant="inputIcon"
                type="password"
                value={senha}
                placeholder="user@123"
                onChange={(event) => setSenha(event.target.value)}
              />
            </div>
          </>
        </div>
        <div className="w-3/4 flex flex-col">
          <Button
            variant="primary"
            disabled={!email || !senha || btnLoading_Submit}
          >
            {btnLoading_Submit ? (
              <CircularProgress
                size={20}
                color="colorPrimary"
                className="relative inset-0 mt-1"
              />
            ) : (
              "Entrar"
            )}
          </Button>
          <div className="text-center mt-1.5">
            <p className="text-sm">
              Não possui uma conta?
              <Link
                to="/cadastro"
                className={buttonVariants({
                  variant: "link",
                  color: "secondary",
                  size: "link",
                })}
              >
                Cria uma conta agora!
              </Link>
            </p>
          </div>
        </div>
      </motion.form>
    </div>
  );
};

export default Login;
