import React, { useState, useRef, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { authCadastro } from "@/context/authCadastro";
import { authProtecao_Rotas } from "@/context/authProtecao_rotas";
import { Link } from "react-router-dom";
// -------- COMPONENTES UI (shadcn)------------
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import { Input } from "@/components/ui/input";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
// -------- ( MATERIAL UI )------------
import CircularProgress from "@mui/material/CircularProgress";
// -------- COMPONENTE ERRO ---------
import Erro from "@/components/componentes/erro";
// ----- BIBLIOTECA DE ANIMACAO (motion) ------
import { motion } from "framer-motion";

const Cliente = () => {
  // ESTADOS
  const [nome, setNome] = useState("");
  const [telefone, setTelefone] = useState("");
  const [dia, setDia] = useState("");
  const [mes, setMes] = useState("");
  const [ano, setAno] = useState("");
  const [erro, setErro] = useState("");
  const [btnLoading_Submit, set_btnLoading_Submit] = useState(false);
  const navigate = useNavigate();
  // ADICIONA ETAPA PARA ROTA
  const { setEtapa } = authProtecao_Rotas();

  // ------- TELEFONE USUARIO ---------
  // FORMATA TELEFONE
  const formatoTelefone = (value) => {
    if (!value) return value;
    const phoneNumber = value.replace(/[^\d]/g, "");
    const phoneNumberLength = phoneNumber.length;

    if (phoneNumberLength < 3) return phoneNumber;
    if (phoneNumberLength < 7) {
      return `(${phoneNumber.slice(0, 2)}) ${phoneNumber.slice(2)}`;
    }
    return `(${phoneNumber.slice(0, 2)}) ${phoneNumber.slice(
      2,
      7
    )}-${phoneNumber.slice(7, 11)}`;
  };
  // ALTERA VALOR DO INPUT PARA O FORMATO
  // GUARDA NO STATE
  const alteraTelefone = (event) => {
    const telefoneFormatado = formatoTelefone(event.target.value);
    setTelefone(telefoneFormatado);
  };
  // ------- NOME USUARIO ---------
  // BLOQUEIA ENTRADA DE NUMEROS e CARACTERES ESPECIAIS
  const alterarNome = (event) => {
    const value = event.target.value;
    // PERMITE APENAS LETRAS E ESPAÇOS
    const formattedValue = value.replace(/[^a-zA-Z\s]/g, "");
    setNome(formattedValue);
  };
  // ------- DATA DE NASCIMENTO USUARIO -------
  const formataData_Contexto = () => {
    return `${dia}-${mes}-${ano}`;
  };

  // VERIFICA SE OS CAMPOS ESTAO PREENCHIDOS
  const isDataNascimentoPreenchida = dia && mes && ano;

  // DATAS PARA OS INPUTS
  // CRIA OS DIAS (1 a 31)
  const dias = Array.from({ length: 31 }, (_, i) =>
    String(i + 1).padStart(2, "0")
  );
  // CRIA OS ANOS (1924 até o ano atual)
  const anoAtual = new Date().getFullYear();
  const anos = Array.from({ length: anoAtual - 1924 + 1 }, (_, i) =>
    String(anoAtual - i)
  );

  // INICIA PAGINA COM INPUT FOCADO
  const inputNome = useRef(null);
  useEffect(() => {
    if (inputNome.current) {
      inputNome.current.focus();
    }
  }, []);

  // ------ ENVIA FORMULARIO -------
  async function EnviarFormulario(event) {
    event.preventDefault();
    set_btnLoading_Submit(true);
    await new Promise((resolve) => setTimeout(resolve, 2000));

    setErro("");
    const anoMinimo = anoAtual - 90;
    // VERIFICA O ANO INDICADO
    if (ano > anoAtual) {
      setErro("Ano indicado é maior que o ano atual");
      set_btnLoading_Submit(false);
      return;
    }
    if (ano < anoMinimo) {
      setErro("Ano indicado é muito distante");
      set_btnLoading_Submit(false);
      return;
    }
    // CONVERTE DATA PARA GUARDAR NO CONTEXTO
    const dataFormatada = formataData_Contexto();

    // GUARDA DADOS NO CONTEXTO
    authCadastro.getState().setUserInfo("nome", nome);
    authCadastro.getState().setUserInfo("tel", telefone);
    authCadastro.getState().setUserInfo("dataNascimento", dataFormatada);

    // AVANCA PAGINA
    setEtapa(4);
    navigate("../cadastro-endereco");

    set_btnLoading_Submit(false);
  }

  return (
    <motion.div
      initial={{ opacity: 0, x: 100 }}
      animate={{ opacity: 1, x: 0 }}
      exit={{ opacity: 0, x: -100 }}
      transition={{ duration: 0.3 }}
      className="h-full"
    >
      <form
        className=" w-full h-full flex flex-col justify-center items-center gap-6 px-4"
        onSubmit={EnviarFormulario}
      >
        {/* CAMPO NOME DO USUARIO */}
        <div className="flex flex-col w-3/4 gap-3">
          <Link to="/" className="sm:hidden mb-2 flex items-center gap-1">
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
          <Label size="subtitle">Informações do usuário</Label>
          {/* COMPONENTE MENSAGEM DE ERRO */}
          <Erro props={erro} />
          <Label size="medium">Nome Completo</Label>
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
                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"
              />
            </svg>

            <Input
              variant="inputIcon"
              type="text"
              value={nome}
              ref={inputNome}
              placeholder="Nome completo"
              onChange={alterarNome}
              maxLength={100}
            />
          </div>
        </div>
        {/* CAMPO DATA DE NASCIMENTO */}
        <div className="flex flex-col w-3/4 gap-3">
          <Label size="medium">Data de nascimento</Label>
          <div className="w-full flex gap-7">
            <section className="flex items-center w-1/4 gap-1">
              <Select onValueChange={(value) => setDia(value)}>
                <SelectTrigger>
                  <SelectValue placeholder="dia" />
                </SelectTrigger>
                <SelectContent className="max-h-56 overflow-y-auto">
                  {dias.map((dia) => (
                    <SelectItem key={dia} value={dia}>
                      {dia}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </section>
            <section className="flex items-center w-2/4 gap-1">
              <Select onValueChange={(value) => setMes(value)}>
                <SelectTrigger>
                  <SelectValue placeholder="mês" />
                </SelectTrigger>
                <SelectContent className="max-h-56 overflow-y-auto">
                  <SelectItem value="01">Janeiro</SelectItem>
                  <SelectItem value="02">Fevereiro</SelectItem>
                  <SelectItem value="03">Março</SelectItem>
                  <SelectItem value="04">Abril</SelectItem>
                  <SelectItem value="05">Maio</SelectItem>
                  <SelectItem value="06">Junho</SelectItem>
                  <SelectItem value="07">Julho</SelectItem>
                  <SelectItem value="08">Agosto</SelectItem>
                  <SelectItem value="09">Setembro</SelectItem>
                  <SelectItem value="10">Outubro</SelectItem>
                  <SelectItem value="11">Novembro</SelectItem>
                  <SelectItem value="12">Dezembro</SelectItem>
                </SelectContent>
              </Select>
            </section>
            <section className="flex items-center w-1/4 gap-1">
              <Select onValueChange={(value) => setAno(value)}>
                <SelectTrigger>
                  <SelectValue placeholder="ano" />
                </SelectTrigger>
                <SelectContent className="max-h-56 overflow-y-auto">
                  {anos.map((anos) => (
                    <SelectItem key={anos} value={anos}>
                      {anos}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </section>
          </div>
        </div>
        {/* CAMPO TELEFONE */}
        <div className="flex flex-col w-3/4 gap-3">
          <Label size="medium">Telefone</Label>
          <div className="relative mb-6">
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
                d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"
              />
            </svg>

            <Input
              variant="inputIcon"
              type="text"
              value={telefone}
              placeholder="(00) 00000-0000"
              onChange={alteraTelefone}
            />
          </div>
          <Button
            variant="primary"
            disabled={
              !nome ||
              !telefone ||
              telefone.length !== 15 ||
              !isDataNascimentoPreenchida ||
              btnLoading_Submit
            }
          >
            {btnLoading_Submit ? (
              <CircularProgress
                size={20}
                color="colorPrimary"
                className="relative inset-0 mt-1"
              />
            ) : (
              "Avançar"
            )}
          </Button>
        </div>
      </form>
    </motion.div>
  );
};

export default Cliente;
