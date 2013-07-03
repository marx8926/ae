--
-- PostgreSQL database dump
--

-- Dumped from database version 9.2.4
-- Dumped by pg_dump version 9.2.4
-- Started on 2013-05-15 00:42:08 PET

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 297 (class 3079 OID 11767)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2860 (class 0 OID 0)
-- Dependencies: 297
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- TOC entry 340 (class 1255 OID 19335)
-- Name: activa_consolida(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION activa_consolida(ids integer) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE consolida SET pausa=false, fecha_reanudacion=current_date WHERE id_miembro=ids;
END;$$;


ALTER FUNCTION public.activa_consolida(ids integer) OWNER TO postgres;

--
-- TOC entry 376 (class 1255 OID 19188)
-- Name: activar_celula(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION activar_celula(idx bigint) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE celula
   SET activo=true
 WHERE id=idx;
	
END;$$;


ALTER FUNCTION public.activar_celula(idx bigint) OWNER TO postgres;

--
-- TOC entry 310 (class 1255 OID 16386)
-- Name: activar_consolidador(bigint, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION activar_consolidador(ids bigint, act boolean) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE consolidador SET  activo= act WHERE id= ids;

END;$$;


ALTER FUNCTION public.activar_consolidador(ids bigint, act boolean) OWNER TO postgres;

--
-- TOC entry 311 (class 1255 OID 16387)
-- Name: apto_consolidador(bigint, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION apto_consolidador(ids bigint, apto boolean) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE consolidador SET  activo=apto WHERE id=ids;
	--UPDATE miembro SET  apto_consolidar= apto WHERE id= ids;

END;$$;


ALTER FUNCTION public.apto_consolidador(ids bigint, apto boolean) OWNER TO postgres;

--
-- TOC entry 312 (class 1255 OID 16388)
-- Name: apto_miembro_consolidador(bigint, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION apto_miembro_consolidador(ids bigint, apto boolean) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE miembro SET  apto_consolidar= apto WHERE id= ids;

END;$$;


ALTER FUNCTION public.apto_miembro_consolidador(ids bigint, apto boolean) OWNER TO postgres;

--
-- TOC entry 313 (class 1255 OID 16389)
-- Name: asistencia_celula(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION asistencia_celula(idx bigint, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT asistencia boolean) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

BEGIN
   
 return query  select p.id, p.nombre, p.apellidos,false as asistencia  from persona p inner join miembro m on
  m.id=p.id inner join celula c on c.id = m.id_celula where c.id=idx;

 return;
 
END;$$;


ALTER FUNCTION public.asistencia_celula(idx bigint, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT asistencia boolean) OWNER TO postgres;

--
-- TOC entry 314 (class 1255 OID 16390)
-- Name: asistencia_tema(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION asistencia_tema(idx bigint, OUT id bigint, OUT id_miembro bigint, OUT nombre character varying, OUT apellidos character varying, OUT estado boolean) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

BEGIN
	
return query   select cl.id, m.id_miembro, p.nombre, p.apellidos, m.estado from clase_cell cl inner 
 join many_clase_celula_has_many_miembro m on m.id_clase_cell=cl.id and cl.id=idx
 inner join persona p on m.id_miembro=p.id order by p.apellidos;


return;
 
END;$$;


ALTER FUNCTION public.asistencia_tema(idx bigint, OUT id bigint, OUT id_miembro bigint, OUT nombre character varying, OUT apellidos character varying, OUT estado boolean) OWNER TO postgres;

--
-- TOC entry 374 (class 1255 OID 19184)
-- Name: celulas_por_red(character varying, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION celulas_por_red(idx character varying, tip integer, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT id_red character varying, OUT tipo smallint, OUT fecha_creacion date, OUT caso integer) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

--tipo 0: evangelistica 1: discipulado

BEGIN
	
return query select  c.id,  p.nombre, p.apellidos ,c.id_red,c.tipo, c.fecha_creacion, case when  c.id_lider_red notnull then 0 
 when c.id_misionero notnull  then 2 when c.id_pastor_ejecutivo notnull then 1  when c.id_lider notnull then 3 end as caso  from 
celula c inner join persona p on  c.tipo=tip and c.id_red=idx and c.activo=true and
case when  c.id_lider_red notnull then c.id_lider_red=p.id 
 when c.id_misionero notnull  then c.id_misionero=p.id
 when c.id_pastor_ejecutivo notnull then c.id_pastor_ejecutivo=p.id
 when c.id_lider notnull then c.id_lider=p.id
 end ;
 
return;
 
END;$$;


ALTER FUNCTION public.celulas_por_red(idx character varying, tip integer, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT id_red character varying, OUT tipo smallint, OUT fecha_creacion date, OUT caso integer) OWNER TO postgres;

--
-- TOC entry 315 (class 1255 OID 16392)
-- Name: delete_asistencia_clase(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION delete_asistencia_clase(matricula bigint) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN

delete from asistencia_clase_curso where id_persona_estudiante IN (select id_persona_estudiante
from matric e where e.id=matricula);

delete from matric where id =matricula;

END; $$;


ALTER FUNCTION public.delete_asistencia_clase(matricula bigint) OWNER TO postgres;

--
-- TOC entry 316 (class 1255 OID 16393)
-- Name: delete_celula(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION delete_celula(idx bigint) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	delete from ubicacion u where u.id IN (select id_ubicacion from celula where id=idx);
	
	delete from celula c where c.id=idx and not exists (select 1 from miembro m where m.id_celula=idx);
	
END;$$;


ALTER FUNCTION public.delete_celula(idx bigint) OWNER TO postgres;

--
-- TOC entry 317 (class 1255 OID 16394)
-- Name: delete_curso_impartido(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION delete_curso_impartido(idx bigint) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	delete from horario h where h.id IN (select id_horario from curso_impartido where id=idx);
	
	delete from curso_impartido c where c.id=idx and not exists (select 1 from matric m where m.id_curso_impartido=idx);
	
END;$$;


ALTER FUNCTION public.delete_curso_impartido(idx bigint) OWNER TO postgres;

--
-- TOC entry 318 (class 1255 OID 16395)
-- Name: delete_herramienta(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION delete_herramienta(idx integer) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	
	delete from herramienta c where c.id=idx and not exists (select 1 from many_herramienta_has_many_consolidacion
	 m where m.id_herramienta=idx);
	
END;$$;


ALTER FUNCTION public.delete_herramienta(idx integer) OWNER TO postgres;

--
-- TOC entry 319 (class 1255 OID 16396)
-- Name: delete_persona(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION delete_persona(idx bigint) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	--eliminar usuario
	delete from usuario u where u.id_persona=idx;

	--elminar descartado

	delete from descartado d where d.id=idx;
	
	--eliminar nuevo convertido

	delete from nuevo_convertido n where n.id=idx;
	
       --eliminar red social
       
	delete from red_social r where r.id_persona=idx;

	--eliminar ubicacion
	delete from ubicacion u where u.id IN (select id_ubicacion from celula where id=idx);

	--eliminar persona

	delete from persona p  where p.id = idx;

	
END;$$;


ALTER FUNCTION public.delete_persona(idx bigint) OWNER TO postgres;

--
-- TOC entry 365 (class 1255 OID 18471)
-- Name: delete_persona_miembro(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION delete_persona_miembro(idx bigint) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN

	-- many usuario
	delete from many_usuario_has_many_rol ur where ur.id_usuario in (select id from usuario where id_persona=idx);
	
	--eliminar usuario
	delete from usuario u where u.id_persona=idx;

	--elminar descartado

	delete from descartado d where d.id=idx;
	
	--eliminar nuevo convertido

	delete from nuevo_convertido n where n.id=idx;

	--eliminar miembro

	delete from miembro m where m.id=idx;
	
       --eliminar red social
       
	delete from red_social r where r.id_persona=idx;

	--eliminar ubicacion
	--delete from ubicacion u where u.id IN (select id_ubicacion from persona where id=idx);

	--eliminar persona

	delete from persona p  where p.id = idx;

	
END;$$;


ALTER FUNCTION public.delete_persona_miembro(idx bigint) OWNER TO postgres;

--
-- TOC entry 321 (class 1255 OID 16398)
-- Name: delete_red(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION delete_red(idx character varying) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	
	delete from red r where r.id=idx and not exists (select 1 from miembro m where m.id_red=idx);
	
END;$$;


ALTER FUNCTION public.delete_red(idx character varying) OWNER TO postgres;

--
-- TOC entry 375 (class 1255 OID 19187)
-- Name: desactivar_celula(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION desactivar_celula(idx bigint) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE celula
   SET activo=false
 WHERE id=idx;
	
END;$$;


ALTER FUNCTION public.desactivar_celula(idx bigint) OWNER TO postgres;

--
-- TOC entry 322 (class 1255 OID 16399)
-- Name: ejemplo(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ejemplo(integer) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
BEGIN
 RETURN $1;
END;
$_$;


ALTER FUNCTION public.ejemplo(integer) OWNER TO postgres;

--
-- TOC entry 323 (class 1255 OID 16400)
-- Name: get_consolidado(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION get_consolidado(idx bigint, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT inicio date, OUT fin date, OUT consolidador bigint, OUT code bigint) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	
return query select p.id, p.nombre, p.apellidos, c.fecha_inicio as inicio, c.fecha_fin as fin,
 c.id_consolidador as consolidador, c.id as code from consolida c
left join persona p on p.id = c.id_miembro where c.id =idx
                 and c.termino=false and c.pausa=false;
                 --select p.id, p.nombre, p.apellidos from consolida c inner join
 --persona p on c.id_nuevo_convertido=idx and p.id=c.id_consolidador;
 
return;
 
END;$$;


ALTER FUNCTION public.get_consolidado(idx bigint, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT inicio date, OUT fin date, OUT consolidador bigint, OUT code bigint) OWNER TO postgres;

--
-- TOC entry 324 (class 1255 OID 16401)
-- Name: get_consolidado_temas(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION get_consolidado_temas(idcon bigint, OUT id bigint, OUT leche integer, OUT inicio timestamp without time zone, OUT fin timestamp without time zone, OUT limite timestamp without time zone, OUT titulo character varying) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	
return query select m.id_consolida as id, id_tema_leche as leche, m.fecha_hora_inicio as inicio, 
m.fecha_hora_fin as fin, m.fecha_hora_limite as limite, t.titulo  from many_consolidacion_has_many_tema_leche m
 inner join tema_leche t on t.id = m.id_tema_leche
  where m.id_consolida = idcon;
               
return;
 
END;$$;


ALTER FUNCTION public.get_consolidado_temas(idcon bigint, OUT id bigint, OUT leche integer, OUT inicio timestamp without time zone, OUT fin timestamp without time zone, OUT limite timestamp without time zone, OUT titulo character varying) OWNER TO postgres;

--
-- TOC entry 325 (class 1255 OID 16402)
-- Name: get_consolidador(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION get_consolidador(idx bigint, OUT id bigint, OUT nombre character varying, OUT apellidos character varying) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	
return query select p.id, p.nombre, p.apellidos from consolida c inner join
 persona p on c.id_nuevo_convertido=idx and p.id=c.id_consolidador;
 
return;
 
END;$$;


ALTER FUNCTION public.get_consolidador(idx bigint, OUT id bigint, OUT nombre character varying, OUT apellidos character varying) OWNER TO postgres;

--
-- TOC entry 326 (class 1255 OID 16403)
-- Name: get_convertido(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION get_convertido(idx bigint, OUT fecha_conversion date, OUT peticion text, OUT consolidado boolean, OUT id bigint, OUT id_celula bigint, OUT id_red character varying, OUT id_lugar integer) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	
return query select * from nuevo_convertido n where n.id = idx and n.consolidado=false;
return;
 
END;$$;


ALTER FUNCTION public.get_convertido(idx bigint, OUT fecha_conversion date, OUT peticion text, OUT consolidado boolean, OUT id bigint, OUT id_celula bigint, OUT id_red character varying, OUT id_lugar integer) OWNER TO postgres;

--
-- TOC entry 327 (class 1255 OID 16404)
-- Name: get_lider_red(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION get_lider_red(idx character varying, OUT id character varying, OUT nombre character varying, OUT apellidos character varying, OUT ids bigint, OUT tipo integer) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	
return query select idx, p.nombre , p.apellidos, p.id as ids, case when  r.id_lider_red notnull then 0 
 when r.id_pastor_asociado notnull then 1  end 
  from red r inner join persona p on r.id=idx and case when  r.id_lider_red notnull then r.id_lider_red=p.id 
 when r.id_pastor_asociado notnull then r.id_pastor_asociado=p.id end;
 
return;
 
END;$$;


ALTER FUNCTION public.get_lider_red(idx character varying, OUT id character varying, OUT nombre character varying, OUT apellidos character varying, OUT ids bigint, OUT tipo integer) OWNER TO postgres;

--
-- TOC entry 328 (class 1255 OID 16405)
-- Name: get_lideres_red(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION get_lideres_red(idx integer, OUT id character varying, OUT idl bigint, OUT nombre character varying, OUT apellidos character varying) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

BEGIN
	
return query  SELECT r.id, l.id as idl, p.nombre ,p.apellidos 
   FROM red r inner join lider_red l on l.id=r.id_lider_red and r.tipo=idx and
   l.activo=true inner join persona p on p.id=r.id_lider_red union 
   SELECT r.id, l.id as idl, p.nombre ,p.apellidos 
   FROM red r inner join pastor_asociado l on l.id=r.id_pastor_asociado and r.tipo=idx and
   l.activo=true inner join persona p on p.id=r.id_lider_red ;

return;
 
END;$$;


ALTER FUNCTION public.get_lideres_red(idx integer, OUT id character varying, OUT idl bigint, OUT nombre character varying, OUT apellidos character varying) OWNER TO postgres;

--
-- TOC entry 329 (class 1255 OID 16406)
-- Name: get_persona(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION get_persona(idx bigint, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT estado_civil smallint, OUT edad smallint, OUT telefono character varying, OUT celular character varying, OUT fecha_nacimiento date, OUT email character varying, OUT website character varying, OUT sexo smallint, OUT id_ubicacion bigint, OUT direccion character varying, OUT referencia text, OUT latitud double precision, OUT longitud double precision, OUT id_ubigeo integer) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	
return query select p.id, p.nombre, p.apellidos,p.estado_civil, p.edad, p.telefono, p.celular, p.fecha_nacimiento,
p.email, p.website, p.sexo, u.id, u.direccion, u.referencia, u.latitud, u.longitud,u.id_ubigeo from persona p inner join ubicacion u on (u.id=p.id_ubicacion) where p.id = idx ;

return;
 
END;$$;


ALTER FUNCTION public.get_persona(idx bigint, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT estado_civil smallint, OUT edad smallint, OUT telefono character varying, OUT celular character varying, OUT fecha_nacimiento date, OUT email character varying, OUT website character varying, OUT sexo smallint, OUT id_ubicacion bigint, OUT direccion character varying, OUT referencia text, OUT latitud double precision, OUT longitud double precision, OUT id_ubigeo integer) OWNER TO postgres;

--
-- TOC entry 330 (class 1255 OID 16407)
-- Name: get_reporte_consolidados_consolidador(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION get_reporte_consolidados_consolidador(idcon bigint, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT conversion date, OUT conid bigint, OUT inicio date, OUT pausa date, OUT reanudacion date, OUT termino boolean, OUT dictada bigint, OUT total bigint) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN

return query select p.id, p.nombre, p.apellidos, n.fecha_conversion as conversion, c.id as conId, c.fecha_inicio as inicio,
c.fecha_pausa as pausa, c.fecha_reanudacion as reanudacion, c.termino,
count(ct.fecha_hora_fin) as dictada, count(ct.fecha_hora_inicio) as total
 from consolida c inner join nuevo_convertido n on n.id=c.id_nuevo_convertido and c.id_consolidador=idcon
 inner join persona p on p.id=n.id
 inner join many_consolidacion_has_many_tema_leche ct 
 on ct.id_consolida=c.id group by p.id, n.fecha_conversion,c.id, c.fecha_inicio,c.fecha_pausa,
  c.fecha_reanudacion,c.termino order by c.fecha_inicio desc;

return;
 
END;$$;


ALTER FUNCTION public.get_reporte_consolidados_consolidador(idcon bigint, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT conversion date, OUT conid bigint, OUT inicio date, OUT pausa date, OUT reanudacion date, OUT termino boolean, OUT dictada bigint, OUT total bigint) OWNER TO postgres;

--
-- TOC entry 331 (class 1255 OID 16408)
-- Name: get_reporte_descartados(date, date); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION get_reporte_descartados(inic date, fini date, OUT id bigint, OUT nombres character varying, OUT apellidos character varying, OUT edad smallint, OUT red character varying, OUT convertido date, OUT inicio_leche date, OUT descarte date, OUT motivo text) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN

return query SELECT p.id, p.nombre AS nombres, p.apellidos, p.edad, m.id_red AS red, m.fecha_obtencion AS convertido, 
c.fecha_inicio AS inicio_leche, d.fecha_descarte AS descarte, d.cometario AS motivo
   FROM consolida c
   JOIN miembro m ON m.id = c.id_miembro AND c.pausa = true
   JOIN persona p ON p.id = m.id
   JOIN descartado d ON d.id = p.id and d.fecha_descarte between inic and fini
  ORDER BY d.fecha_descarte DESC;

return;
 
END;$$;


ALTER FUNCTION public.get_reporte_descartados(inic date, fini date, OUT id bigint, OUT nombres character varying, OUT apellidos character varying, OUT edad smallint, OUT red character varying, OUT convertido date, OUT inicio_leche date, OUT descarte date, OUT motivo text) OWNER TO postgres;

--
-- TOC entry 332 (class 1255 OID 16409)
-- Name: get_reporte_ganar(integer, date, date); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION get_reporte_ganar(tipo integer, inicio date, fin date, OUT id character varying, OUT almas bigint, OUT nombre character varying, OUT apellidos character varying) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

-- tipo: 0:mujeres 1: hombres 2: mixta

BEGIN

IF tipo=0 THEN

	create temporary table prueba1 as (select * from get_lideres_red(2) union select * from get_lideres_red(4));

ELSIF tipo=1 THEN
	create temporary table prueba1 as (select * from get_lideres_red(1) union select * from get_lideres_red(3));

ELSE
	create temporary table prueba1 as (select * from get_lideres_red(0) );

END IF;	

return query  select p.id, (select count(a.*) from nuevo_convertido a where a.id_red=p.id and a.id!=p.idl and 
a.fecha_conversion between inicio and fin) as almas, p.nombre, p.apellidos 
from prueba1 p group by p.id, p.idl, p.nombre, p.apellidos ;

drop table prueba1;

return;
 
END;$$;


ALTER FUNCTION public.get_reporte_ganar(tipo integer, inicio date, fin date, OUT id character varying, OUT almas bigint, OUT nombre character varying, OUT apellidos character varying) OWNER TO postgres;

--
-- TOC entry 333 (class 1255 OID 16410)
-- Name: get_reporte_herramientas(timestamp without time zone, timestamp without time zone); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION get_reporte_herramientas(inicio timestamp without time zone, fin timestamp without time zone, OUT idx bigint, OUT nombre character varying, OUT apellidos character varying, OUT id bigint, OUT id_red character varying, OUT fecha_inicio date, OUT termino boolean, OUT titulo character varying, OUT hecho boolean, OUT aplicacion timestamp without time zone, OUT propuesta timestamp without time zone) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN

return query select p.id as idx, p.nombre, p.apellidos,c.id,m.id_red, c.fecha_inicio,  c.termino
,h.nombre as titulo, hc.hecho, hc.fecha_hora_aplicacion as aplicacion , hc.fecha_hora_propuesta as propuesta
from consolida c inner join

miembro m on m.id=c.id_miembro inner join persona p on p.id=m.id inner  join 
many_herramienta_has_many_consolidacion hc on hc.id_consolida=c.id and 
hc.fecha_hora_propuesta between inicio and fin inner join herramienta h
on h.id=hc.id_herramienta;

return;
 
END;$$;


ALTER FUNCTION public.get_reporte_herramientas(inicio timestamp without time zone, fin timestamp without time zone, OUT idx bigint, OUT nombre character varying, OUT apellidos character varying, OUT id bigint, OUT id_red character varying, OUT fecha_inicio date, OUT termino boolean, OUT titulo character varying, OUT hecho boolean, OUT aplicacion timestamp without time zone, OUT propuesta timestamp without time zone) OWNER TO postgres;

--
-- TOC entry 334 (class 1255 OID 16411)
-- Name: get_reporte_leche_espiritual_consolida(date, date, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION get_reporte_leche_espiritual_consolida(inic date, fini date, tipo integer, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT red character varying, OUT celula bigint, OUT fecha_inicio date, OUT fecha_fin date, OUT pausa boolean, OUT termino boolean, OUT fecha date, OUT leche integer, OUT titulo character varying, OUT inicio timestamp without time zone, OUT limite timestamp without time zone, OUT fin timestamp without time zone) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN

return query select p.id, p.nombre, p.apellidos, mi.id_red as red , mi.id_celula as celula, c.fecha_inicio, c.fecha_fin,
 c.pausa, c.termino, mi.fecha_conversion as fecha, t.id_leche_espiritual as leche, t.titulo,
  m.fecha_hora_inicio as inicio ,
m.fecha_hora_limite as limite , m.fecha_hora_fin as fin from tema_leche t inner join many_consolidacion_has_many_tema_leche m
on m.id_tema_leche = t.id and t.id_leche_espiritual=tipo 

inner join consolida c on c.id= m.id_consolida and c.fecha_inicio between inic and fini
 inner join nuevo_convertido mi on c.id_miembro=mi.id  inner join persona p on p.id= mi.id;

return;
 
END;$$;


ALTER FUNCTION public.get_reporte_leche_espiritual_consolida(inic date, fini date, tipo integer, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT red character varying, OUT celula bigint, OUT fecha_inicio date, OUT fecha_fin date, OUT pausa boolean, OUT termino boolean, OUT fecha date, OUT leche integer, OUT titulo character varying, OUT inicio timestamp without time zone, OUT limite timestamp without time zone, OUT fin timestamp without time zone) OWNER TO postgres;

--
-- TOC entry 335 (class 1255 OID 16412)
-- Name: get_reporte_lugar_ganar(integer, date, date); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION get_reporte_lugar_ganar(tipo integer, inicio date, fin date, OUT id integer, OUT nombre character varying, OUT almas bigint, OUT sexo integer) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

-- tipo: 0:mujeres 1: hombres 
BEGIN

 create temporary table lugar_g  as (select n.*,p.sexo from nuevo_convertido n inner join persona p on p.id=n.id);

IF tipo=0 THEN

	return query select l.id, l.nombre, (select count(lu.sexo) 
	from lugar_g lu where lu.id_lugar=l.id and lu.sexo=2 and lu.fecha_conversion between inicio and fin ) as almas, 
	2 sexo from lugar l ;


ELSE
 return query select l.id, l.nombre, (select count(lu.sexo) 
 from lugar_g lu where lu.id_lugar=l.id and lu.sexo=1 and lu.fecha_conversion between inicio and fin) as almas,
  1 sexo from lugar l ;
END IF;	

drop table lugar_g;

return;
 
END;$$;


ALTER FUNCTION public.get_reporte_lugar_ganar(tipo integer, inicio date, fin date, OUT id integer, OUT nombre character varying, OUT almas bigint, OUT sexo integer) OWNER TO postgres;

--
-- TOC entry 336 (class 1255 OID 16413)
-- Name: get_reporte_no_consolidados(date, date); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION get_reporte_no_consolidados(inicio date, fin date, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT edad smallint, OUT red character varying, OUT celula bigint, OUT fecha date) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN

return query select p.id, p.nombre, p.apellidos, p.edad, m.id_red as red, m.id_celula as celula, m.fecha_conversion as fecha from persona p inner join nuevo_convertido
 m on m.id=p.id -- and m.consolidado=false
  and not exists
(select 1 from consolida c where c.id_miembro=m.id ) and m.fecha_conversion between inicio and fin;

return;
 
END;$$;


ALTER FUNCTION public.get_reporte_no_consolidados(inicio date, fin date, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT edad smallint, OUT red character varying, OUT celula bigint, OUT fecha date) OWNER TO postgres;

--
-- TOC entry 337 (class 1255 OID 16414)
-- Name: get_reporte_nuevos_convertidos(date, date, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION get_reporte_nuevos_convertidos(inicio date, fin date, flag boolean, OUT id bigint, OUT nombres character varying, OUT apellidos character varying, OUT edad smallint, OUT red character varying, OUT celula bigint, OUT conversion date, OUT consolidado boolean) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN

return query SELECT p.id, p.nombre AS nombres, p.apellidos, p.edad, n.id_red AS red, n.id_celula AS celula,
  n.fecha_conversion AS conversion,n.consolidado
   FROM nuevo_convertido n
   JOIN persona p ON n.id = p.id and n.consolidado=flag and n.fecha_conversion between inicio and fin;

return;
 
END;$$;


ALTER FUNCTION public.get_reporte_nuevos_convertidos(inicio date, fin date, flag boolean, OUT id bigint, OUT nombres character varying, OUT apellidos character varying, OUT edad smallint, OUT red character varying, OUT celula bigint, OUT conversion date, OUT consolidado boolean) OWNER TO postgres;

--
-- TOC entry 366 (class 1255 OID 18916)
-- Name: get_reporte_nuevos_convertidos_lugar(date, date); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION get_reporte_nuevos_convertidos_lugar(inicio date, fin date, OUT red character varying, OUT nombres character varying, OUT apellidos character varying, OUT lugar character varying, OUT consolidado boolean, OUT convertido date) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN

return query select   n.id_red as red, p.nombre, p.apellidos, l.nombre as lugar,n.consolidado,
 n.fecha_conversion as convertido
from persona p
 inner join nuevo_convertido n on n.id=p.id and n.consolidado=false and n.fecha_conversion between inicio and fin inner join lugar l on n.id_lugar=l.id;

return;
 
END;$$;


ALTER FUNCTION public.get_reporte_nuevos_convertidos_lugar(inicio date, fin date, OUT red character varying, OUT nombres character varying, OUT apellidos character varying, OUT lugar character varying, OUT consolidado boolean, OUT convertido date) OWNER TO postgres;

--
-- TOC entry 338 (class 1255 OID 16415)
-- Name: get_ubigeo(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION get_ubigeo(idx integer, OUT id1 integer, OUT coddepartamento smallint, OUT codprovincia smallint, OUT coddistrito smallint, OUT departamento character varying, OUT provincia character varying, OUT distrito character varying, OUT lat double precision, OUT long double precision) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	
return query select * from ubigeo where id=idx;
return;
 
END;$$;


ALTER FUNCTION public.get_ubigeo(idx integer, OUT id1 integer, OUT coddepartamento smallint, OUT codprovincia smallint, OUT coddistrito smallint, OUT departamento character varying, OUT provincia character varying, OUT distrito character varying, OUT lat double precision, OUT long double precision) OWNER TO postgres;

--
-- TOC entry 339 (class 1255 OID 16416)
-- Name: insert_asistencia_clase(bigint, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION insert_asistencia_clase(estudiante bigint, clase integer) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN

insert into asistencia_clase_curso(id_persona_estudiante, id_clase_curso)
 select estudiante, clase where not exists (select id_persona_estudiante,
id_clase_curso from asistencia_clase_curso a where a.id_persona_estudiante=estudiante and
 a.id_clase_curso=clase);

END; $$;


ALTER FUNCTION public.insert_asistencia_clase(estudiante bigint, clase integer) OWNER TO postgres;

--
-- TOC entry 371 (class 1255 OID 19104)
-- Name: insert_celula(integer, character varying, character varying, bigint, character varying, integer, bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION insert_celula(tip integer, fam character varying, tel character varying, ubi bigint, red character varying, caso integer, idx bigint) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
   if caso=0 then --misionero
   
	INSERT INTO celula(
            fecha_creacion, tipo, familia, telefono, id_ubicacion, id_red, 
            id_misionero, id_pastor_ejecutivo, id_lider_red,id_lider, activo)
    VALUES (current_date, tip, fam, tel, ubi, red, idx, NULL, NULL, NULL,TRUE);

    elsif caso=1 then --pastor ejecutivo
	INSERT INTO celula(
            fecha_creacion, tipo, familia, telefono, id_ubicacion, id_red, 
            id_misionero, id_pastor_ejecutivo, id_lider_red,id_lider, activo)
    VALUES (current_date, tip, fam, tel, ubi, red, NULL,idx, NULL,NULL, TRUE);

    elsif caso=2 then --lider de red 
	INSERT INTO celula(
            fecha_creacion, tipo, familia, telefono, id_ubicacion, id_red, 
            id_misionero, id_pastor_ejecutivo, id_lider_red,id_lider, activo)
    VALUES (current_date, tip, fam, tel, ubi, red, NULL, NULL,idx, NULL,TRUE);

    else  --lider
	INSERT INTO celula(
            fecha_creacion, tipo, familia, telefono, id_ubicacion, id_red, 
            id_misionero, id_pastor_ejecutivo, id_lider_red,id_lider, activo)
    VALUES (current_date, tip, fam, tel, ubi, red, NULL, NULL,NULL,idx,TRUE);
    end if;
    
    
END;$$;


ALTER FUNCTION public.insert_celula(tip integer, fam character varying, tel character varying, ubi bigint, red character varying, caso integer, idx bigint) OWNER TO postgres;

--
-- TOC entry 341 (class 1255 OID 16418)
-- Name: insert_clase_cell_celulas(bigint, bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION insert_clase_cell_celulas(idcell bigint, idclass_cell bigint) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	insert into  many_clase_celula_has_many_miembro(id_clase_cell, id_miembro) 
	SELECT cl.id, m.id FROM clase_cell cl inner join miembro m on m.id_celula=cl.id_celula and cl.id=idclass_cell and 
cl.id_celula = idcell WHERE NOT EXISTS (select 1 from many_clase_celula_has_many_miembro mn
 where mn.id_clase_cell=idclass_cell and mn.id_miembro=m.id);

END;$$;


ALTER FUNCTION public.insert_clase_cell_celulas(idcell bigint, idclass_cell bigint) OWNER TO postgres;

--
-- TOC entry 342 (class 1255 OID 16419)
-- Name: insert_clase_cell_miembro(integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION insert_clase_cell_miembro(miembro integer, clase_cell integer) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	INSERT INTO many_clase_celula_has_many_miembro(id_clase_cell, id_miembro) 
	select clase_cell, miembro WHERE NOT EXISTS ( SELECT id_clase_cell, id_miembro
	 from many_clase_celula_has_many_miembro m where m.id_clase_cell=clase_cell and m.id_miembro=miembro);

END;$$;


ALTER FUNCTION public.insert_clase_cell_miembro(miembro integer, clase_cell integer) OWNER TO postgres;

--
-- TOC entry 343 (class 1255 OID 16420)
-- Name: insert_consolida_herramienta(integer, bigint, timestamp without time zone); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION insert_consolida_herramienta(tool integer, consolida bigint, propuesta timestamp without time zone) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
   
   INSERT INTO many_herramienta_has_many_consolidacion(
            id_herramienta, id_consolida, fecha_hora_propuesta)
    VALUES (tool, consolida, propuesta);

    
END;$$;


ALTER FUNCTION public.insert_consolida_herramienta(tool integer, consolida bigint, propuesta timestamp without time zone) OWNER TO postgres;

--
-- TOC entry 344 (class 1255 OID 16421)
-- Name: insert_consolida_leche(bigint, integer, timestamp without time zone, timestamp without time zone); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION insert_consolida_leche(idc bigint, idtl integer, fin timestamp without time zone, flim timestamp without time zone) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
   
   INSERT INTO many_consolidacion_has_many_tema_leche(id_consolida, id_tema_leche, fecha_hora_inicio,
                   fecha_hora_limite) VALUES (idc, idtl,fIn,fLim);
    
END;$$;


ALTER FUNCTION public.insert_consolida_leche(idc bigint, idtl integer, fin timestamp without time zone, flim timestamp without time zone) OWNER TO postgres;

--
-- TOC entry 345 (class 1255 OID 16422)
-- Name: insert_matricula(date, bigint, bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION insert_matricula(fecha1 date, persona bigint, curso bigint) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	INSERT INTO matric(fecha,id_persona_estudiante, id_curso_impartido) 
	select fecha1, persona,curso WHERE NOT EXISTS ( SELECT fecha, id_persona_estudiante, id_curso_impartido
	 from matric m where m.id_persona_estudiante=persona and m.id_curso_impartido=curso);

END;$$;


ALTER FUNCTION public.insert_matricula(fecha1 date, persona bigint, curso bigint) OWNER TO postgres;

--
-- TOC entry 346 (class 1255 OID 16423)
-- Name: ruta_celula(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ruta_celula(idx bigint) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
DECLARE
	r RECORD;
BEGIN
   FOR r IN
      select a.direccion as "direccion" 
      from tema_celula t inner join archivo a on
       a.id_tema_celula=t.id where t.id=idx
   LOOP
     RETURN NEXT r;
   END LOOP;
 RETURN;
END;$$;


ALTER FUNCTION public.ruta_celula(idx bigint) OWNER TO postgres;

--
-- TOC entry 347 (class 1255 OID 16424)
-- Name: temas_celula(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION temas_celula(idx bigint, OUT id bigint, OUT titulo character varying, OUT autor character varying, OUT dia character varying, OUT hora time without time zone, OUT dictado date) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

BEGIN
	
return query  select cl.id , t.titutlo as titulo, t.autor, h.dia , h.hora_inicio as hora, cl.fecha_dicto as dictado  from clase_cell cl inner join tema_celula t 
on t.id=cl.id_tema_celula and cl.id_celula=idx 
inner join horario h on h.id=cl.id_horario order by cl.id DESC;
 

return;
 
END;$$;


ALTER FUNCTION public.temas_celula(idx bigint, OUT id bigint, OUT titulo character varying, OUT autor character varying, OUT dia character varying, OUT hora time without time zone, OUT dictado date) OWNER TO postgres;

--
-- TOC entry 348 (class 1255 OID 16425)
-- Name: termino_consolida(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION termino_consolida(idx bigint) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE consolida SET fecha_fin=current_date,termino=TRUE WHERE id=idx;
	
END;$$;


ALTER FUNCTION public.termino_consolida(idx bigint) OWNER TO postgres;

--
-- TOC entry 373 (class 1255 OID 19167)
-- Name: update_cell(integer, character varying, character varying, character varying, bigint, bigint, bigint, bigint, bigint, character varying, text, double precision, double precision, integer, bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION update_cell(tip integer, fam character varying, tel character varying, red character varying, mision bigint, pastor bigint, lider bigint, liderl bigint, idx bigint, dir character varying, refer text, lati double precision, longitu double precision, ubigeo integer, idubi bigint) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN


UPDATE ubicacion SET direccion= dir, latitud = lati, longitud=longitu, referencia=refer, id_ubigeo=ubigeo
	where id=idubi;
UPDATE celula
   SET tipo=tip, familia= fam, telefono=tel,
       id_red=red, id_misionero=mision, id_pastor_ejecutivo=pastor, id_lider_red=lider,
       id_lider = liderl
 WHERE id=idx;

END;$$;


ALTER FUNCTION public.update_cell(tip integer, fam character varying, tel character varying, red character varying, mision bigint, pastor bigint, lider bigint, liderl bigint, idx bigint, dir character varying, refer text, lati double precision, longitu double precision, ubigeo integer, idubi bigint) OWNER TO postgres;

--
-- TOC entry 349 (class 1255 OID 16427)
-- Name: update_clase_cell(bigint, real); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION update_clase_cell(idx bigint, monto real) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE clase_cell SET ofrenda=monto, fecha_dicto= current_date WHERE id=idx;
END;$$;


ALTER FUNCTION public.update_clase_cell(idx bigint, monto real) OWNER TO postgres;

--
-- TOC entry 350 (class 1255 OID 16428)
-- Name: update_clase_cell_miembro(bigint, bigint, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION update_clase_cell_miembro(miemb bigint, clase bigint, est boolean) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	update many_clase_celula_has_many_miembro set estado=est where id_clase_cell=clase and id_miembro=miemb;

END;$$;


ALTER FUNCTION public.update_clase_cell_miembro(miemb bigint, clase bigint, est boolean) OWNER TO postgres;

--
-- TOC entry 351 (class 1255 OID 16429)
-- Name: update_consol_lecha(timestamp without time zone, bigint, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION update_consol_lecha(fecha_fin timestamp without time zone, idcon bigint, tema_leche integer) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE many_consolidacion_has_many_tema_leche SET fecha_hora_fin=fecha_fin WHERE id_consolida=idcon and id_tema_leche= tema_leche;
END;$$;


ALTER FUNCTION public.update_consol_lecha(fecha_fin timestamp without time zone, idcon bigint, tema_leche integer) OWNER TO postgres;

--
-- TOC entry 352 (class 1255 OID 16430)
-- Name: update_consolida(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION update_consolida(ids integer) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE consolida SET pausa=true, fecha_pausa=current_date WHERE id=ids;
END;$$;


ALTER FUNCTION public.update_consolida(ids integer) OWNER TO postgres;

--
-- TOC entry 353 (class 1255 OID 16431)
-- Name: update_consolida_consolidador(integer, integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION update_consolida_consolidador(id1_con integer, id2_con integer, id_p integer) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE consolida SET id_consolidador= id2_con WHERE id_consolidador=id1_con and id=id_p;
END;$$;


ALTER FUNCTION public.update_consolida_consolidador(id1_con integer, id2_con integer, id_p integer) OWNER TO postgres;

--
-- TOC entry 354 (class 1255 OID 16432)
-- Name: update_consolida_herramienta(integer, bigint, timestamp without time zone); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION update_consolida_herramienta(tool integer, consolida bigint, final timestamp without time zone) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN

   UPDATE many_herramienta_has_many_consolidacion
   SET  fecha_hora_aplicacion=final,     hecho=TRUE
 WHERE id_herramienta=tool and id_consolida=consolida;

END;$$;


ALTER FUNCTION public.update_consolida_herramienta(tool integer, consolida bigint, final timestamp without time zone) OWNER TO postgres;

--
-- TOC entry 377 (class 1255 OID 19297)
-- Name: update_consolida_leche(integer, timestamp without time zone, bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION update_consolida_leche(id_leche integer, fecha_fin timestamp without time zone, id bigint) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE many_consolidacion_has_many_tema_leche SET fecha_hora_fin=fecha_fin  
	where id_tema_leche=id_leche and id_consolida=id;
END;$$;


ALTER FUNCTION public.update_consolida_leche(id_leche integer, fecha_fin timestamp without time zone, id bigint) OWNER TO postgres;

--
-- TOC entry 355 (class 1255 OID 16434)
-- Name: update_red(character varying, integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION update_red(idx character varying, tip integer, iglesia integer) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
UPDATE red SET tipo=tip, activo=true, id_iglesia=iglesia  WHERE id=idx;
END;$$;


ALTER FUNCTION public.update_red(idx character varying, tip integer, iglesia integer) OWNER TO postgres;

--
-- TOC entry 356 (class 1255 OID 16435)
-- Name: update_red(character varying, integer, integer, bigint, bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION update_red(idx character varying, tip integer, iglesia integer, lider bigint, pastor bigint) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
UPDATE red SET tipo=tip, activo=true, id_iglesia=iglesia, id_lider_red=lider, id_pastor_asociado=pastor  WHERE id=idx;
END;$$;


ALTER FUNCTION public.update_red(idx character varying, tip integer, iglesia integer, lider bigint, pastor bigint) OWNER TO postgres;

--
-- TOC entry 357 (class 1255 OID 16436)
-- Name: update_redm(character varying, integer, smallint, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION update_redm(code character varying, iglesia integer, tip smallint, state boolean) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
	UPDATE red
   SET activo=true
 WHERE id=code;

END;$$;


ALTER FUNCTION public.update_redm(code character varying, iglesia integer, tip smallint, state boolean) OWNER TO postgres;

--
-- TOC entry 320 (class 1255 OID 16437)
-- Name: update_ubicacion(bigint, character varying, text, double precision, double precision, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION update_ubicacion(idx bigint, dir character varying, refe text, lat double precision, lng double precision, ubigeo integer) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$
BEGIN
UPDATE ubicacion
   SET  direccion=dir, latitud=lat, longitud=lng, referencia=refe, id_ubigeo=ubigeo
 WHERE id=idx;
END;$$;


ALTER FUNCTION public.update_ubicacion(idx bigint, dir character varying, refe text, lat double precision, lng double precision, ubigeo integer) OWNER TO postgres;

--
-- TOC entry 372 (class 1255 OID 19132)
-- Name: ver_celula(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ver_celula(idx bigint, OUT id bigint, OUT tipo smallint, OUT familia character varying, OUT telefono character varying, OUT activo boolean, OUT id_red character varying, OUT caso integer, OUT ubi_id bigint, OUT direccion character varying, OUT referencia text, OUT latitud double precision, OUT longitud double precision, OUT id_ubigeo integer, OUT coddistrito smallint, OUT codprovincia smallint, OUT coddepartamento smallint, OUT idp bigint, OUT nombre character varying, OUT apellidos character varying) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

BEGIN
	
return query select c.id, c.tipo, c.familia, c.telefono, c.activo,c.id_red,case when  c.id_lider_red notnull then 0 
 when c.id_misionero notnull  then 2 when c.id_pastor_ejecutivo notnull then 1 when  c.id_lider notnull then 3 end , u.id as ubi_id,
  u.direccion, u.referencia, u.latitud, u.longitud, u.id_ubigeo, ub.coddistrito, ub.codprovincia, ub.coddepartamento, p.id as idp
 ,p.nombre, p.apellidos
  from celula c inner join ubicacion u on u.id=c.id_ubicacion and c.id=idx inner join 
persona p on case when  c.id_lider_red notnull then c.id_lider_red=p.id 
 when c.id_misionero notnull  then c.id_misionero=p.id
 when c.id_pastor_ejecutivo notnull then c.id_pastor_ejecutivo=p.id
 when c.id_lider notnull then c.id_lider=p.id
 end 
inner join ubigeo ub on u.id_ubigeo=ub.id
 ;

return;
 
END;$$;


ALTER FUNCTION public.ver_celula(idx bigint, OUT id bigint, OUT tipo smallint, OUT familia character varying, OUT telefono character varying, OUT activo boolean, OUT id_red character varying, OUT caso integer, OUT ubi_id bigint, OUT direccion character varying, OUT referencia text, OUT latitud double precision, OUT longitud double precision, OUT id_ubigeo integer, OUT coddistrito smallint, OUT codprovincia smallint, OUT coddepartamento smallint, OUT idp bigint, OUT nombre character varying, OUT apellidos character varying) OWNER TO postgres;

--
-- TOC entry 358 (class 1255 OID 16439)
-- Name: ver_celulas(character varying, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ver_celulas(idx character varying, tip integer, OUT nombre character varying, OUT apellidos character varying, OUT id bigint, OUT id_red character varying) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

BEGIN
   

create temporary table prueba as (select * from lista_redes);
return query select prueba.nombre, prueba.apellidos, c.id, c.id_red from prueba 
inner join celula c on c.id_red=prueba.id and c.tipo=tip and prueba.id=idx and c.activo=true;

drop table prueba;

 return;
 
END;$$;


ALTER FUNCTION public.ver_celulas(idx character varying, tip integer, OUT nombre character varying, OUT apellidos character varying, OUT id bigint, OUT id_red character varying) OWNER TO postgres;

--
-- TOC entry 370 (class 1255 OID 19186)
-- Name: ver_celulas(character varying, integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ver_celulas(idx character varying, tip integer, caso integer, OUT nombre character varying, OUT apellidos character varying, OUT id bigint, OUT id_red character varying) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

BEGIN
	if caso=0 then --misionero
		return query select p.nombre, p.apellidos, p.id,c.id_red from persona p 
		inner join celula c on c.id_red=idx and 
		c.tipo=tip and c.id_misionero=p.id;
	elsif caso=1 then --pastor ejecutivo
		return query select p.nombre, p.apellidos, p.id,c.id_red from persona p 
		inner join celula c on c.id_red=idx and 
		c.tipo=tip and c.id_pastor_ejecutivo=p.id;
	elsif caso=2 then --lider de red
		return query select p.nombre, p.apellidos, p.id,c.id_red from persona p 
		inner join celula c on c.id_red=idx and 
		c.tipo=tip and c.id_lider_red=p.id;
	else --lider
		return query select p.nombre, p.apellidos, p.id,c.id_red from persona p 
		inner join celula c on c.id_red=idx and 
		c.tipo=tip and c.id_lider=p.id;
	end if;
   
 return;
 
END;$$;


ALTER FUNCTION public.ver_celulas(idx character varying, tip integer, caso integer, OUT nombre character varying, OUT apellidos character varying, OUT id bigint, OUT id_red character varying) OWNER TO postgres;

--
-- TOC entry 359 (class 1255 OID 16441)
-- Name: ver_distrito(integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ver_distrito(idd integer, idp integer, OUT ids integer, OUT coddep smallint, OUT codprov smallint, OUT coddis smallint, OUT dep character varying, OUT prov character varying, OUT dist character varying, OUT lati double precision, OUT longi double precision) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

BEGIN
	
return query select * from ubigeo where coddepartamento=idD and codprovincia=idP group by coddistrito, distrito,id;
return;
 
END;$$;


ALTER FUNCTION public.ver_distrito(idd integer, idp integer, OUT ids integer, OUT coddep smallint, OUT codprov smallint, OUT coddis smallint, OUT dep character varying, OUT prov character varying, OUT dist character varying, OUT lati double precision, OUT longi double precision) OWNER TO postgres;

--
-- TOC entry 369 (class 1255 OID 19063)
-- Name: ver_lideres_celulas(character varying, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ver_lideres_celulas(idx character varying, caso integer, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT id_red character varying) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

BEGIN
	if caso=0 then --misionero
		
		create temporary table pruebaT as (select * from lista_misionero_celula_sin);

	elsif caso=1 then --pastor ejecutivo

		create temporary table pruebaT as (select * from lista_pastor_eje_celula_sin);

	elsif caso=2 then --lider de red
		create temporary table pruebaT as (select * from lista_lider_red_celula_sin);


	else --lider

		create temporary table pruebaT as (select * from lista_lideres_celula_sin);

	end if;
	
	return query select pruebaT.id, pruebaT.nombre, pruebaT.apellidos, miembro.id_red from pruebaT inner join
		      miembro on miembro.id=pruebaT.id and miembro.id_red=idx;

	drop table pruebaT;

 return;
 
END;$$;


ALTER FUNCTION public.ver_lideres_celulas(idx character varying, caso integer, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT id_red character varying) OWNER TO postgres;

--
-- TOC entry 367 (class 1255 OID 19057)
-- Name: ver_lideres_red_to_celulas(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ver_lideres_red_to_celulas(idx character varying, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT id_red character varying) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

BEGIN
	create temporary table pruebaLR as (select * from lista_lider_red_celula_con);

	
	
	return query select pruebaLR.id, pruebaLR.nombre, pruebaLR.apellidos, miembro.id_red from pruebaLR inner join
		      miembro on miembro.id=pruebaLR.id and miembro.id_red=idx;

	drop table pruebaLR;

 return;
 
END;$$;


ALTER FUNCTION public.ver_lideres_red_to_celulas(idx character varying, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT id_red character varying) OWNER TO postgres;

--
-- TOC entry 368 (class 1255 OID 19062)
-- Name: ver_lideres_to_celulas(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ver_lideres_to_celulas(idx character varying, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT id_red character varying) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

BEGIN
	create temporary table pruebaL as (select * from lista_lideres_celula_con);

	
	
	return query select pruebaL.id, pruebaL.nombre, pruebaL.apellidos, miembro.id_red from pruebaL inner join
		      miembro on miembro.id=pruebaL.id and miembro.id_red=idx;

	drop table pruebaL;

 return;
 
END;$$;


ALTER FUNCTION public.ver_lideres_to_celulas(idx character varying, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT id_red character varying) OWNER TO postgres;

--
-- TOC entry 360 (class 1255 OID 16444)
-- Name: ver_misionero_to_celulas(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ver_misionero_to_celulas(idx character varying, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT id_red character varying) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

BEGIN
	create temporary table prueba as (select * from lista_misionero_celula_con);

	
	
	return query select prueba.id, prueba.nombre, prueba.apellidos, miembro.id_red from prueba inner join
		      miembro on miembro.id=prueba.id and miembro.id_red=idx;

	drop table prueba;

 return;
 
END;$$;


ALTER FUNCTION public.ver_misionero_to_celulas(idx character varying, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT id_red character varying) OWNER TO postgres;

--
-- TOC entry 361 (class 1255 OID 16445)
-- Name: ver_pastor_eje_to_celulas(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ver_pastor_eje_to_celulas(idx character varying, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT id_red character varying) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

BEGIN
	create temporary table prueba as (select * from lista_pastor_eje_celula_con);

	
	
	return query select prueba.id, prueba.nombre, prueba.apellidos, miembro.id_red from prueba inner join
		      miembro on miembro.id=prueba.id and miembro.id_red=idx;

	drop table prueba;

 return;
 
END;$$;


ALTER FUNCTION public.ver_pastor_eje_to_celulas(idx character varying, OUT id bigint, OUT nombre character varying, OUT apellidos character varying, OUT id_red character varying) OWNER TO postgres;

--
-- TOC entry 362 (class 1255 OID 16446)
-- Name: ver_provincia(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ver_provincia(idx integer, OUT cod smallint, OUT prov character varying) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

BEGIN
	
return query  select codprovincia, provincia from ubigeo u where u.coddepartamento=idx group by codprovincia, provincia;

return;
 
END;$$;


ALTER FUNCTION public.ver_provincia(idx integer, OUT cod smallint, OUT prov character varying) OWNER TO postgres;

--
-- TOC entry 363 (class 1255 OID 16447)
-- Name: ver_tema_celula(bigint); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ver_tema_celula(idx bigint, OUT id bigint, OUT celula bigint, OUT tipo smallint, OUT red character varying, OUT tema_celula integer, OUT titulo character varying, OUT dia character varying, OUT hora_inicio time without time zone, OUT hora_fin time without time zone) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

BEGIN
   
 return query  select c.id, c.id_celula as celula , cel.tipo, cel.id_red as red, c.id_tema_celula as tema_celula, t.titutlo 
as titulo, h.dia, h.hora_inicio, h.hora_fin from clase_cell c inner join horario h on h.id=c.id_horario and c.id=idx 
inner join tema_celula t on t.id=c.id_tema_celula inner join celula cel on c.id_celula=cel.id; 

 return;
 
END;$$;


ALTER FUNCTION public.ver_tema_celula(idx bigint, OUT id bigint, OUT celula bigint, OUT tipo smallint, OUT red character varying, OUT tema_celula integer, OUT titulo character varying, OUT dia character varying, OUT hora_inicio time without time zone, OUT hora_fin time without time zone) OWNER TO postgres;

--
-- TOC entry 364 (class 1255 OID 16448)
-- Name: ver_tema_leche(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ver_tema_leche(idx integer, OUT id integer, OUT titulo character varying, OUT id_leche_espiritual integer) RETURNS SETOF record
    LANGUAGE plpgsql
    AS $$

BEGIN
   
 return query select t.id, t.titulo, t.id_leche_espiritual from tema_leche t where t.id_leche_espiritual = idx ; 

 return;
 
END;$$;


ALTER FUNCTION public.ver_tema_leche(idx integer, OUT id integer, OUT titulo character varying, OUT id_leche_espiritual integer) OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 178 (class 1259 OID 16449)
-- Name: archivo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE archivo (
    id bigint NOT NULL,
    direccion text NOT NULL,
    peso bigint,
    tipo character varying(25),
    extension character varying(10),
    nombre text,
    fecha date,
    id_tema_celula integer,
    id_tema_curso integer,
    id_leche_espiritual integer
);


ALTER TABLE public.archivo OWNER TO postgres;

--
-- TOC entry 2861 (class 0 OID 0)
-- Dependencies: 178
-- Name: COLUMN archivo.peso; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN archivo.peso IS 'tamaño del archivo en Bytes';


--
-- TOC entry 2862 (class 0 OID 0)
-- Dependencies: 178
-- Name: COLUMN archivo.tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN archivo.tipo IS 'el tipo de archivo: PDF, EXCEL, WORD, CORELDRAW, etc.';


--
-- TOC entry 2863 (class 0 OID 0)
-- Dependencies: 178
-- Name: COLUMN archivo.extension; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN archivo.extension IS 'la extension que tiene el archivo: .pdf, .xls, .xlsx, .doc, .docx, etc';


--
-- TOC entry 2864 (class 0 OID 0)
-- Dependencies: 178
-- Name: COLUMN archivo.id_leche_espiritual; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN archivo.id_leche_espiritual IS 'Identificador de la clase leche';


--
-- TOC entry 179 (class 1259 OID 16455)
-- Name: archivo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE archivo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.archivo_id_seq OWNER TO postgres;

--
-- TOC entry 2865 (class 0 OID 0)
-- Dependencies: 179
-- Name: archivo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE archivo_id_seq OWNED BY archivo.id;


--
-- TOC entry 180 (class 1259 OID 16457)
-- Name: area_vision; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE area_vision (
    id character(2) NOT NULL,
    nombre character varying(20) NOT NULL
);


ALTER TABLE public.area_vision OWNER TO postgres;

--
-- TOC entry 181 (class 1259 OID 16460)
-- Name: asistencia_clase_curso; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE asistencia_clase_curso (
    id_persona_estudiante bigint NOT NULL,
    id_clase_curso integer NOT NULL,
    nota double precision DEFAULT 0 NOT NULL,
    asistencia boolean DEFAULT false NOT NULL
);


ALTER TABLE public.asistencia_clase_curso OWNER TO postgres;

--
-- TOC entry 2866 (class 0 OID 0)
-- Dependencies: 181
-- Name: COLUMN asistencia_clase_curso.id_persona_estudiante; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN asistencia_clase_curso.id_persona_estudiante IS 'Identificador de la persona';


--
-- TOC entry 182 (class 1259 OID 16465)
-- Name: celula; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE celula (
    id bigint NOT NULL,
    fecha_creacion date NOT NULL,
    tipo smallint NOT NULL,
    familia character varying(100) NOT NULL,
    telefono character varying(20),
    id_ubicacion bigint NOT NULL,
    id_red character varying(10),
    id_misionero bigint,
    id_pastor_ejecutivo bigint,
    id_lider_red bigint,
    activo boolean DEFAULT true,
    id_lider bigint
);


ALTER TABLE public.celula OWNER TO postgres;

--
-- TOC entry 2867 (class 0 OID 0)
-- Dependencies: 182
-- Name: COLUMN celula.tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN celula.tipo IS 'Evangelica, Discipulado';


--
-- TOC entry 2868 (class 0 OID 0)
-- Dependencies: 182
-- Name: COLUMN celula.familia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN celula.familia IS 'Nombre de la familia que acoge la célula';


--
-- TOC entry 2869 (class 0 OID 0)
-- Dependencies: 182
-- Name: COLUMN celula.id_ubicacion; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN celula.id_ubicacion IS 'Identificador de la Ubicación, en este caso es un entero autoincremental';


--
-- TOC entry 2870 (class 0 OID 0)
-- Dependencies: 182
-- Name: COLUMN celula.id_misionero; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN celula.id_misionero IS 'Identificador de la persona';


--
-- TOC entry 2871 (class 0 OID 0)
-- Dependencies: 182
-- Name: COLUMN celula.id_pastor_ejecutivo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN celula.id_pastor_ejecutivo IS 'Identificador de la persona';


--
-- TOC entry 2872 (class 0 OID 0)
-- Dependencies: 182
-- Name: COLUMN celula.id_lider_red; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN celula.id_lider_red IS 'Identificador de la persona';


--
-- TOC entry 2873 (class 0 OID 0)
-- Dependencies: 182
-- Name: COLUMN celula.id_lider; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN celula.id_lider IS '--identificador de lider de celula';


--
-- TOC entry 183 (class 1259 OID 16469)
-- Name: celula_discipulado; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW celula_discipulado AS
    SELECT celula.id, celula.fecha_creacion, celula.tipo, celula.familia, celula.telefono, celula.id_ubicacion, celula.id_red, celula.id_misionero, celula.id_pastor_ejecutivo, celula.id_lider_red, celula.activo FROM celula WHERE ((celula.tipo = 1) AND (celula.activo = true));


ALTER TABLE public.celula_discipulado OWNER TO postgres;

--
-- TOC entry 184 (class 1259 OID 16473)
-- Name: celula_evangelistica; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW celula_evangelistica AS
    SELECT celula.id, celula.fecha_creacion, celula.tipo, celula.familia, celula.telefono, celula.id_ubicacion, celula.id_red, celula.id_misionero, celula.id_pastor_ejecutivo, celula.id_lider_red, celula.activo FROM celula WHERE ((celula.tipo = 0) AND (celula.activo = true));


ALTER TABLE public.celula_evangelistica OWNER TO postgres;

--
-- TOC entry 185 (class 1259 OID 16477)
-- Name: celula_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE celula_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.celula_id_seq OWNER TO postgres;

--
-- TOC entry 2874 (class 0 OID 0)
-- Dependencies: 185
-- Name: celula_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE celula_id_seq OWNED BY celula.id;


--
-- TOC entry 186 (class 1259 OID 16479)
-- Name: clase_cell; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE clase_cell (
    ofrenda real,
    fecha_dicto date,
    id bigint NOT NULL,
    id_horario integer,
    id_celula bigint,
    id_tema_celula integer
);


ALTER TABLE public.clase_cell OWNER TO postgres;

--
-- TOC entry 187 (class 1259 OID 16482)
-- Name: clase_cell_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE clase_cell_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.clase_cell_id_seq OWNER TO postgres;

--
-- TOC entry 2875 (class 0 OID 0)
-- Dependencies: 187
-- Name: clase_cell_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE clase_cell_id_seq OWNED BY clase_cell.id;


--
-- TOC entry 188 (class 1259 OID 16484)
-- Name: clase_curso; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE clase_curso (
    id integer NOT NULL,
    fecha_dicto date,
    id_curso_impartido bigint NOT NULL,
    tema integer
);


ALTER TABLE public.clase_curso OWNER TO postgres;

--
-- TOC entry 189 (class 1259 OID 16487)
-- Name: clase_curso_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE clase_curso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.clase_curso_id_seq OWNER TO postgres;

--
-- TOC entry 2876 (class 0 OID 0)
-- Dependencies: 189
-- Name: clase_curso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE clase_curso_id_seq OWNED BY clase_curso.id;


--
-- TOC entry 190 (class 1259 OID 16489)
-- Name: consolida; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE consolida (
    fecha_inicio date NOT NULL,
    fecha_fin date,
    pausa boolean DEFAULT false NOT NULL,
    fecha_pausa date,
    fecha_reanudacion date,
    id bigint NOT NULL,
    termino boolean DEFAULT false,
    id_nuevo_convertido bigint NOT NULL,
    id_miembro bigint NOT NULL,
    id_consolidador bigint
);


ALTER TABLE public.consolida OWNER TO postgres;

--
-- TOC entry 2877 (class 0 OID 0)
-- Dependencies: 190
-- Name: TABLE consolida; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE consolida IS 'Tabla que almacena los datos de la consolidacion.';


--
-- TOC entry 2878 (class 0 OID 0)
-- Dependencies: 190
-- Name: COLUMN consolida.fecha_inicio; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN consolida.fecha_inicio IS 'Fecha en la que se inicio la consolidadción.';


--
-- TOC entry 2879 (class 0 OID 0)
-- Dependencies: 190
-- Name: COLUMN consolida.fecha_fin; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN consolida.fecha_fin IS 'Fecha en la que finalizó la consolidación. Si es null, entonces la persona fue descartada o aun esta en consolidadción.';


--
-- TOC entry 2880 (class 0 OID 0)
-- Dependencies: 190
-- Name: COLUMN consolida.pausa; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN consolida.pausa IS 'Pausa la consolidacion por si el consolidando no pudiera estar por algun tiempo';


--
-- TOC entry 2881 (class 0 OID 0)
-- Dependencies: 190
-- Name: COLUMN consolida.fecha_pausa; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN consolida.fecha_pausa IS 'Fecha en la que se pauso la consolidación';


--
-- TOC entry 2882 (class 0 OID 0)
-- Dependencies: 190
-- Name: COLUMN consolida.fecha_reanudacion; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN consolida.fecha_reanudacion IS 'Fecha en la que se reanuda la consolidación';


--
-- TOC entry 2883 (class 0 OID 0)
-- Dependencies: 190
-- Name: COLUMN consolida.id_nuevo_convertido; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN consolida.id_nuevo_convertido IS 'Identificador de la persona';


--
-- TOC entry 2884 (class 0 OID 0)
-- Dependencies: 190
-- Name: COLUMN consolida.id_miembro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN consolida.id_miembro IS 'Identificador de la persona';


--
-- TOC entry 2885 (class 0 OID 0)
-- Dependencies: 190
-- Name: COLUMN consolida.id_consolidador; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN consolida.id_consolidador IS 'Identificador de la persona';


--
-- TOC entry 191 (class 1259 OID 16494)
-- Name: consolida_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE consolida_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.consolida_id_seq OWNER TO postgres;

--
-- TOC entry 2886 (class 0 OID 0)
-- Dependencies: 191
-- Name: consolida_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE consolida_id_seq OWNED BY consolida.id;


--
-- TOC entry 192 (class 1259 OID 16496)
-- Name: miembro; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE miembro (
    apto_consolidar boolean DEFAULT false NOT NULL,
    fecha_obtencion date NOT NULL,
    activo boolean DEFAULT true,
    id bigint NOT NULL,
    id_celula bigint,
    id_red character varying(10)
);


ALTER TABLE public.miembro OWNER TO postgres;

--
-- TOC entry 2887 (class 0 OID 0)
-- Dependencies: 192
-- Name: COLUMN miembro.apto_consolidar; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN miembro.apto_consolidar IS 'Indica si el miembro esta apto para consolidar. Una persona puede ser consolidador si ha pasado el curso de consolidación y/o ha madurado espiritualmete.';


--
-- TOC entry 2888 (class 0 OID 0)
-- Dependencies: 192
-- Name: COLUMN miembro.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN miembro.id IS 'Identificador de la persona';


--
-- TOC entry 193 (class 1259 OID 16501)
-- Name: persona; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE persona (
    id bigint NOT NULL,
    nombre character varying(150) NOT NULL,
    apellidos character varying(100) NOT NULL,
    estado_civil smallint NOT NULL,
    edad smallint NOT NULL,
    telefono character varying(20),
    celular character varying(20),
    fecha_nacimiento date NOT NULL,
    email character varying(100),
    website character varying(100),
    sexo smallint NOT NULL,
    id_ubicacion bigint NOT NULL,
    id_iglesia integer
);


ALTER TABLE public.persona OWNER TO postgres;

--
-- TOC entry 2889 (class 0 OID 0)
-- Dependencies: 193
-- Name: TABLE persona; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE persona IS 'Tabla en la que se almacena la imformacion básica de la persona';


--
-- TOC entry 2890 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN persona.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN persona.id IS 'Identificador de la persona';


--
-- TOC entry 2891 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN persona.nombre; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN persona.nombre IS 'Nombres de la persona';


--
-- TOC entry 2892 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN persona.apellidos; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN persona.apellidos IS 'Apellidos de la persona';


--
-- TOC entry 2893 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN persona.estado_civil; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN persona.estado_civil IS 'Estado civil de la persona:
0 - Soltero,
1 - Casado,
2 - Viudo,
3 - Divorciado';


--
-- TOC entry 2894 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN persona.edad; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN persona.edad IS 'Edad de la persona';


--
-- TOC entry 2895 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN persona.telefono; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN persona.telefono IS 'telefono fijo de la persona';


--
-- TOC entry 2896 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN persona.celular; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN persona.celular IS 'Telefono celular de la persona';


--
-- TOC entry 2897 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN persona.fecha_nacimiento; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN persona.fecha_nacimiento IS 'Fecha en la que nació la persona';


--
-- TOC entry 2898 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN persona.email; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN persona.email IS 'Dirección e-mail de la persona';


--
-- TOC entry 2899 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN persona.website; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN persona.website IS 'Página web de la persona';


--
-- TOC entry 2900 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN persona.sexo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN persona.sexo IS 'Sexo de la persona:1-Masculino2-femenino';


--
-- TOC entry 2901 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN persona.id_ubicacion; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN persona.id_ubicacion IS 'Identificador de la Ubicación, en este caso es un entero autoincremental';


--
-- TOC entry 2902 (class 0 OID 0)
-- Dependencies: 193
-- Name: COLUMN persona.id_iglesia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN persona.id_iglesia IS 'Identificador de la iglesia';


--
-- TOC entry 194 (class 1259 OID 16507)
-- Name: consolidado_termino; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW consolidado_termino AS
    SELECT p.id, p.nombre AS nom, p.apellidos AS ap, m.id_red AS red, m.id_celula AS cell, c.fecha_inicio AS inicio, c.fecha_fin AS fin, c.fecha_reanudacion AS reanuda, q.nombre AS nomq, q.apellidos AS apq FROM (((consolida c JOIN persona p ON (((p.id = c.id_miembro) AND (c.termino = true)))) JOIN miembro m ON ((c.id_miembro = m.id))) JOIN persona q ON ((q.id = c.id_consolidador)));


ALTER TABLE public.consolidado_termino OWNER TO postgres;

--
-- TOC entry 195 (class 1259 OID 16512)
-- Name: consolidador; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE consolidador (
    fecha_obtencion date NOT NULL,
    activo boolean DEFAULT true,
    fecha_fin date,
    id bigint NOT NULL
);


ALTER TABLE public.consolidador OWNER TO postgres;

--
-- TOC entry 2903 (class 0 OID 0)
-- Dependencies: 195
-- Name: COLUMN consolidador.fecha_obtencion; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN consolidador.fecha_obtencion IS 'Fecha en la qie se hiso consolidador de red.';


--
-- TOC entry 2904 (class 0 OID 0)
-- Dependencies: 195
-- Name: COLUMN consolidador.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN consolidador.id IS 'Identificador de la persona';


--
-- TOC entry 196 (class 1259 OID 16516)
-- Name: consolidador_consolidado; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW consolidador_consolidado AS
    SELECT pc.id AS idc, pc.nombre AS nombrec, pc.apellidos AS apellidosc, pd.id AS idd, pd.nombre AS nombred, pd.apellidos, d.id, d.fecha_inicio AS inicio FROM (((consolidador c JOIN consolida d ON (((d.id_consolidador = c.id) AND (d.termino = false)))) JOIN persona pd ON ((pd.id = d.id_miembro))) JOIN persona pc ON ((pc.id = c.id))) WHERE (d.termino = false);


ALTER TABLE public.consolidador_consolidado OWNER TO postgres;

--
-- TOC entry 197 (class 1259 OID 16521)
-- Name: consolidando; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW consolidando AS
    SELECT p.id, p.nombre AS nom, p.apellidos AS ap, m.id_red AS red, m.id_celula AS cell, c.id AS conid, c.fecha_inicio AS inicio, c.fecha_pausa AS pausa, c.fecha_reanudacion AS reanuda, q.nombre, q.apellidos FROM (((consolida c JOIN persona p ON ((((p.id = c.id_miembro) AND (c.pausa = false)) AND (c.termino = false)))) JOIN miembro m ON (((m.id = c.id_miembro) AND (m.activo = true)))) JOIN persona q ON ((q.id = c.id_consolidador)));


ALTER TABLE public.consolidando OWNER TO postgres;

--
-- TOC entry 198 (class 1259 OID 16526)
-- Name: criterio_evaluacion; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE criterio_evaluacion (
    id integer NOT NULL,
    id_curso_impartido bigint NOT NULL
);


ALTER TABLE public.criterio_evaluacion OWNER TO postgres;

--
-- TOC entry 199 (class 1259 OID 16529)
-- Name: criterio_evaluacion_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE criterio_evaluacion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.criterio_evaluacion_id_seq OWNER TO postgres;

--
-- TOC entry 2905 (class 0 OID 0)
-- Dependencies: 199
-- Name: criterio_evaluacion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE criterio_evaluacion_id_seq OWNED BY criterio_evaluacion.id;


--
-- TOC entry 200 (class 1259 OID 16531)
-- Name: curso; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE curso (
    id bigint NOT NULL,
    fecha_creacion date NOT NULL,
    descripcion text NOT NULL,
    numero_sesiones smallint NOT NULL,
    activo boolean DEFAULT true NOT NULL,
    titulo character varying(120)
);


ALTER TABLE public.curso OWNER TO postgres;

--
-- TOC entry 201 (class 1259 OID 16538)
-- Name: curso_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE curso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.curso_id_seq OWNER TO postgres;

--
-- TOC entry 2906 (class 0 OID 0)
-- Dependencies: 201
-- Name: curso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE curso_id_seq OWNED BY curso.id;


--
-- TOC entry 202 (class 1259 OID 16540)
-- Name: curso_impartido; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE curso_impartido (
    fecha_creacion date NOT NULL,
    id bigint NOT NULL,
    id_curso bigint NOT NULL,
    id_persona_docente bigint NOT NULL,
    id_horario integer NOT NULL,
    activo boolean DEFAULT true NOT NULL,
    estado_matricula boolean NOT NULL,
    fecha_inicio date NOT NULL,
    fecha_fin date,
    id_local bigint
);


ALTER TABLE public.curso_impartido OWNER TO postgres;

--
-- TOC entry 2907 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN curso_impartido.id_persona_docente; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN curso_impartido.id_persona_docente IS 'Identificador de la persona';


--
-- TOC entry 203 (class 1259 OID 16544)
-- Name: curso_impartido_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE curso_impartido_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.curso_impartido_id_seq OWNER TO postgres;

--
-- TOC entry 2908 (class 0 OID 0)
-- Dependencies: 203
-- Name: curso_impartido_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE curso_impartido_id_seq OWNED BY curso_impartido.id;


--
-- TOC entry 204 (class 1259 OID 16546)
-- Name: descartado; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE descartado (
    cometario text,
    fecha_descarte date NOT NULL,
    id bigint NOT NULL
);


ALTER TABLE public.descartado OWNER TO postgres;

--
-- TOC entry 2909 (class 0 OID 0)
-- Dependencies: 204
-- Name: TABLE descartado; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE descartado IS 'tabla que almacena la informacion del descartado';


--
-- TOC entry 2910 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN descartado.cometario; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN descartado.cometario IS 'Breve descripción de porque se descarto a ala persona';


--
-- TOC entry 2911 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN descartado.fecha_descarte; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN descartado.fecha_descarte IS 'Fecha en la que se descarto a la persona';


--
-- TOC entry 2912 (class 0 OID 0)
-- Dependencies: 204
-- Name: COLUMN descartado.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN descartado.id IS 'Identificador de la persona';


--
-- TOC entry 205 (class 1259 OID 16552)
-- Name: descartar; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW descartar AS
    SELECT consolida.id, consolida.fecha_inicio AS inicio_leche, miembro.fecha_obtencion AS convertido, miembro.id_red AS red, miembro.id_celula AS celula, persona.id AS codigo, persona.nombre, persona.apellidos, persona.edad, persona.telefono FROM ((consolida LEFT JOIN miembro ON ((miembro.id = consolida.id_miembro))) LEFT JOIN persona ON ((persona.id = miembro.id))) WHERE (((consolida.pausa = false) AND (miembro.activo = true)) AND (consolida.termino = false));


ALTER TABLE public.descartar OWNER TO postgres;

--
-- TOC entry 206 (class 1259 OID 16557)
-- Name: docente; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE docente (
    fecha_inicio date NOT NULL,
    descripcion text NOT NULL,
    activo boolean DEFAULT true,
    fecha_fin date,
    id_persona bigint NOT NULL
);


ALTER TABLE public.docente OWNER TO postgres;

--
-- TOC entry 2913 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN docente.id_persona; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN docente.id_persona IS 'Identificador de la persona';


--
-- TOC entry 207 (class 1259 OID 16564)
-- Name: eje; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW eje AS
    SELECT consolida.fecha_inicio, consolida.fecha_fin, consolida.pausa, consolida.fecha_pausa, consolida.fecha_reanudacion, consolida.id, consolida.termino, consolida.id_nuevo_convertido, consolida.id_miembro, consolida.id_consolidador FROM consolida;


ALTER TABLE public.eje OWNER TO postgres;

--
-- TOC entry 208 (class 1259 OID 16568)
-- Name: encargado; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE encargado (
    fecha_obtencion date NOT NULL,
    activo boolean DEFAULT true,
    fecha_fin date,
    id bigint NOT NULL
);


ALTER TABLE public.encargado OWNER TO postgres;

--
-- TOC entry 2914 (class 0 OID 0)
-- Dependencies: 208
-- Name: COLUMN encargado.fecha_obtencion; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN encargado.fecha_obtencion IS 'Fecha en la que se hiso encargado';


--
-- TOC entry 2915 (class 0 OID 0)
-- Dependencies: 208
-- Name: COLUMN encargado.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN encargado.id IS 'Identificador de la persona';


--
-- TOC entry 209 (class 1259 OID 16572)
-- Name: estudiante; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE estudiante (
    fecha_inicio date,
    activo boolean DEFAULT true,
    fecha_fin date,
    id bigint NOT NULL
);


ALTER TABLE public.estudiante OWNER TO postgres;

--
-- TOC entry 2916 (class 0 OID 0)
-- Dependencies: 209
-- Name: COLUMN estudiante.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN estudiante.id IS 'Identificador de la persona';


--
-- TOC entry 210 (class 1259 OID 16576)
-- Name: evaluacion; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE evaluacion (
    id_persona_estudiante bigint NOT NULL,
    id_criterio_evaluacion integer NOT NULL
);


ALTER TABLE public.evaluacion OWNER TO postgres;

--
-- TOC entry 2917 (class 0 OID 0)
-- Dependencies: 210
-- Name: COLUMN evaluacion.id_persona_estudiante; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evaluacion.id_persona_estudiante IS 'Identificador de la persona';


--
-- TOC entry 211 (class 1259 OID 16579)
-- Name: evento; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE evento (
    id integer NOT NULL,
    nombre character varying(200) NOT NULL,
    descripcion text,
    "fechaIni" date,
    "fechaFin" date,
    id_ubicacion bigint
);


ALTER TABLE public.evento OWNER TO postgres;

--
-- TOC entry 212 (class 1259 OID 16585)
-- Name: evento_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE evento_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.evento_id_seq OWNER TO postgres;

--
-- TOC entry 2918 (class 0 OID 0)
-- Dependencies: 212
-- Name: evento_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE evento_id_seq OWNED BY evento.id;


--
-- TOC entry 213 (class 1259 OID 16587)
-- Name: evento_realizado; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE evento_realizado (
    id bigint NOT NULL,
    id_persona bigint,
    id_evento integer NOT NULL
);


ALTER TABLE public.evento_realizado OWNER TO postgres;

--
-- TOC entry 2919 (class 0 OID 0)
-- Dependencies: 213
-- Name: COLUMN evento_realizado.id_persona; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN evento_realizado.id_persona IS 'Identificador de la persona';


--
-- TOC entry 214 (class 1259 OID 16590)
-- Name: evento_realizado_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE evento_realizado_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.evento_realizado_id_seq OWNER TO postgres;

--
-- TOC entry 2920 (class 0 OID 0)
-- Dependencies: 214
-- Name: evento_realizado_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE evento_realizado_id_seq OWNED BY evento_realizado.id;


--
-- TOC entry 215 (class 1259 OID 16592)
-- Name: herramienta; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE herramienta (
    id integer NOT NULL,
    nombre character varying(20) NOT NULL,
    tiempo_optimo interval NOT NULL
);


ALTER TABLE public.herramienta OWNER TO postgres;

--
-- TOC entry 2921 (class 0 OID 0)
-- Dependencies: 215
-- Name: COLUMN herramienta.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN herramienta.id IS 'Identificador de la herramienta. Es entero y autoincremental.';


--
-- TOC entry 2922 (class 0 OID 0)
-- Dependencies: 215
-- Name: COLUMN herramienta.nombre; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN herramienta.nombre IS 'Nombre de la herramineta.';


--
-- TOC entry 216 (class 1259 OID 16595)
-- Name: herramienta_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE herramienta_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.herramienta_id_seq OWNER TO postgres;

--
-- TOC entry 2923 (class 0 OID 0)
-- Dependencies: 216
-- Name: herramienta_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE herramienta_id_seq OWNED BY herramienta.id;


--
-- TOC entry 217 (class 1259 OID 16597)
-- Name: horario; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE horario (
    id bigint NOT NULL,
    dia character varying(10) NOT NULL,
    hora_inicio time without time zone NOT NULL,
    hora_fin time without time zone NOT NULL
);


ALTER TABLE public.horario OWNER TO postgres;

--
-- TOC entry 2924 (class 0 OID 0)
-- Dependencies: 217
-- Name: TABLE horario; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE horario IS 'nombre del dia.';


--
-- TOC entry 218 (class 1259 OID 16600)
-- Name: horario_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE horario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.horario_id_seq OWNER TO postgres;

--
-- TOC entry 2925 (class 0 OID 0)
-- Dependencies: 218
-- Name: horario_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE horario_id_seq OWNED BY horario.id;


--
-- TOC entry 219 (class 1259 OID 16602)
-- Name: iglesia; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE iglesia (
    id integer NOT NULL,
    nombre character varying(30) NOT NULL,
    telefono character varying(20),
    id_ubicacion bigint
);


ALTER TABLE public.iglesia OWNER TO postgres;

--
-- TOC entry 2926 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN iglesia.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN iglesia.id IS 'Identificador de la iglesia';


--
-- TOC entry 2927 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN iglesia.telefono; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN iglesia.telefono IS 'Telefono fijo de la iglesia';


--
-- TOC entry 2928 (class 0 OID 0)
-- Dependencies: 219
-- Name: COLUMN iglesia.id_ubicacion; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN iglesia.id_ubicacion IS 'Identificador de la Ubicación, en este caso es un entero autoincremental';


--
-- TOC entry 220 (class 1259 OID 16605)
-- Name: iglesia_area; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE iglesia_area (
    id_iglesia integer NOT NULL,
    id_area_vision character(2) NOT NULL
);


ALTER TABLE public.iglesia_area OWNER TO postgres;

--
-- TOC entry 2929 (class 0 OID 0)
-- Dependencies: 220
-- Name: COLUMN iglesia_area.id_iglesia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN iglesia_area.id_iglesia IS 'Identificador de la iglesia';


--
-- TOC entry 221 (class 1259 OID 16608)
-- Name: iglesia_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE iglesia_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.iglesia_id_seq OWNER TO postgres;

--
-- TOC entry 2930 (class 0 OID 0)
-- Dependencies: 221
-- Name: iglesia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE iglesia_id_seq OWNED BY iglesia.id;


--
-- TOC entry 222 (class 1259 OID 16610)
-- Name: informe; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE informe (
    id bigint NOT NULL,
    id_lider_red_receptor character(50),
    id_pastor_asociado_receptor character(50),
    id_encargado_receptor character(50),
    id_misionero_receptor character(50),
    fecha date,
    id_lider_red bigint,
    id_pastor_asociado bigint,
    id_misionero bigint,
    id_encargado bigint,
    id_lider bigint,
    id_pastor_ejecutivo bigint
);


ALTER TABLE public.informe OWNER TO postgres;

--
-- TOC entry 2931 (class 0 OID 0)
-- Dependencies: 222
-- Name: COLUMN informe.id_lider_red; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN informe.id_lider_red IS 'Identificador de la persona';


--
-- TOC entry 2932 (class 0 OID 0)
-- Dependencies: 222
-- Name: COLUMN informe.id_pastor_asociado; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN informe.id_pastor_asociado IS 'Identificador de la persona';


--
-- TOC entry 2933 (class 0 OID 0)
-- Dependencies: 222
-- Name: COLUMN informe.id_misionero; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN informe.id_misionero IS 'Identificador de la persona';


--
-- TOC entry 2934 (class 0 OID 0)
-- Dependencies: 222
-- Name: COLUMN informe.id_encargado; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN informe.id_encargado IS 'Identificador de la persona';


--
-- TOC entry 2935 (class 0 OID 0)
-- Dependencies: 222
-- Name: COLUMN informe.id_lider; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN informe.id_lider IS 'Identificador de la persona';


--
-- TOC entry 2936 (class 0 OID 0)
-- Dependencies: 222
-- Name: COLUMN informe.id_pastor_ejecutivo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN informe.id_pastor_ejecutivo IS 'Identificador de la persona';


--
-- TOC entry 223 (class 1259 OID 16613)
-- Name: informe_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE informe_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.informe_id_seq OWNER TO postgres;

--
-- TOC entry 2937 (class 0 OID 0)
-- Dependencies: 223
-- Name: informe_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE informe_id_seq OWNED BY informe.id;


--
-- TOC entry 224 (class 1259 OID 16615)
-- Name: leche_espiritual; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE leche_espiritual (
    id integer NOT NULL,
    nombre character varying(100) NOT NULL,
    resumen text NOT NULL,
    fecha_creacion date NOT NULL
);


ALTER TABLE public.leche_espiritual OWNER TO postgres;

--
-- TOC entry 2938 (class 0 OID 0)
-- Dependencies: 224
-- Name: TABLE leche_espiritual; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE leche_espiritual IS 'Tabla enla que se almacenan las clase de leche espiritual';


--
-- TOC entry 2939 (class 0 OID 0)
-- Dependencies: 224
-- Name: COLUMN leche_espiritual.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN leche_espiritual.id IS 'Identificador de la clase leche';


--
-- TOC entry 225 (class 1259 OID 16621)
-- Name: leche_espiritual_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE leche_espiritual_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.leche_espiritual_id_seq OWNER TO postgres;

--
-- TOC entry 2940 (class 0 OID 0)
-- Dependencies: 225
-- Name: leche_espiritual_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE leche_espiritual_id_seq OWNED BY leche_espiritual.id;


--
-- TOC entry 226 (class 1259 OID 16623)
-- Name: lider; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE lider (
    fecha_obtencion date NOT NULL,
    activo boolean DEFAULT true,
    fecha_fin date,
    id bigint NOT NULL
);


ALTER TABLE public.lider OWNER TO postgres;

--
-- TOC entry 2941 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN lider.fecha_obtencion; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN lider.fecha_obtencion IS 'Fecha en la que se hiso lider.';


--
-- TOC entry 2942 (class 0 OID 0)
-- Dependencies: 226
-- Name: COLUMN lider.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN lider.id IS 'Identificador de la persona';


--
-- TOC entry 227 (class 1259 OID 16627)
-- Name: lider_red; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE lider_red (
    fecha_obtencion date NOT NULL,
    activo boolean DEFAULT true,
    fecha_fin date,
    id bigint NOT NULL
);


ALTER TABLE public.lider_red OWNER TO postgres;

--
-- TOC entry 2943 (class 0 OID 0)
-- Dependencies: 227
-- Name: COLUMN lider_red.fecha_obtencion; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN lider_red.fecha_obtencion IS 'Fecha en la qie se hiso lider de red.';


--
-- TOC entry 2944 (class 0 OID 0)
-- Dependencies: 227
-- Name: COLUMN lider_red.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN lider_red.id IS 'Identificador de la persona';


--
-- TOC entry 228 (class 1259 OID 16631)
-- Name: lista_asistencia_celula; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_asistencia_celula AS
    SELECT p.id, p.nombre, p.apellidos, false AS asistencia FROM ((persona p JOIN miembro m ON ((m.id = p.id))) JOIN celula c ON ((c.id = m.id_celula))) WHERE (c.id = 2);


ALTER TABLE public.lista_asistencia_celula OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 16636)
-- Name: ubicacion; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE ubicacion (
    id bigint NOT NULL,
    direccion character varying(150) NOT NULL,
    latitud double precision NOT NULL,
    longitud double precision NOT NULL,
    referencia text,
    id_ubigeo integer NOT NULL
);


ALTER TABLE public.ubicacion OWNER TO postgres;

--
-- TOC entry 2945 (class 0 OID 0)
-- Dependencies: 229
-- Name: TABLE ubicacion; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE ubicacion IS 'Tabla en la que se almacena la ubicación de lapersona, iglesia, red, celula u otra entidad';


--
-- TOC entry 2946 (class 0 OID 0)
-- Dependencies: 229
-- Name: COLUMN ubicacion.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN ubicacion.id IS 'Identificador de la Ubicación, en este caso es un entero autoincremental';


--
-- TOC entry 2947 (class 0 OID 0)
-- Dependencies: 229
-- Name: COLUMN ubicacion.direccion; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN ubicacion.direccion IS 'Direccion de la vivienda';


--
-- TOC entry 2948 (class 0 OID 0)
-- Dependencies: 229
-- Name: COLUMN ubicacion.latitud; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN ubicacion.latitud IS 'Latitud y Longitud.';


--
-- TOC entry 2949 (class 0 OID 0)
-- Dependencies: 229
-- Name: COLUMN ubicacion.referencia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN ubicacion.referencia IS 'Una breve descripción del entorno cerda de la ubicación';


--
-- TOC entry 285 (class 1259 OID 19139)
-- Name: lista_celula_lider; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_celula_lider AS
    SELECT c.id, p.nombre, p.apellidos, 3 AS tip_lider, c.id_red, c.tipo, u.direccion, c.fecha_creacion, c.activo FROM ((celula c JOIN persona p ON ((p.id = c.id_lider))) JOIN ubicacion u ON ((u.id = c.id_ubicacion)));


ALTER TABLE public.lista_celula_lider OWNER TO postgres;

--
-- TOC entry 289 (class 1259 OID 19189)
-- Name: lista_celula_lider_act; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_celula_lider_act AS
    SELECT c.id, p.nombre, p.apellidos, 3 AS tip_lider, c.id_red, c.tipo, u.direccion, c.fecha_creacion, c.activo FROM ((celula c JOIN persona p ON (((p.id = c.id_lider) AND (c.activo = true)))) JOIN ubicacion u ON ((u.id = c.id_ubicacion)));


ALTER TABLE public.lista_celula_lider_act OWNER TO postgres;

--
-- TOC entry 292 (class 1259 OID 19205)
-- Name: lista_celula_lider_des; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_celula_lider_des AS
    SELECT c.id, p.nombre, p.apellidos, 3 AS tip_lider, c.id_red, c.tipo, u.direccion, c.fecha_creacion, c.activo FROM ((celula c JOIN persona p ON (((p.id = c.id_lider) AND (c.activo = false)))) JOIN ubicacion u ON ((u.id = c.id_ubicacion)));


ALTER TABLE public.lista_celula_lider_des OWNER TO postgres;

--
-- TOC entry 286 (class 1259 OID 19144)
-- Name: lista_celula_lider_red; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_celula_lider_red AS
    SELECT c.id, p.nombre, p.apellidos, 0 AS tip_lider, c.id_red, c.tipo, u.direccion, c.fecha_creacion, c.activo FROM ((celula c JOIN persona p ON ((p.id = c.id_lider_red))) JOIN ubicacion u ON ((u.id = c.id_ubicacion)));


ALTER TABLE public.lista_celula_lider_red OWNER TO postgres;

--
-- TOC entry 290 (class 1259 OID 19194)
-- Name: lista_celula_lider_red_act; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_celula_lider_red_act AS
    SELECT c.id, p.nombre, p.apellidos, 0 AS tip_lider, c.id_red, c.tipo, u.direccion, c.fecha_creacion, c.activo FROM ((celula c JOIN persona p ON (((p.id = c.id_lider_red) AND (c.activo = true)))) JOIN ubicacion u ON ((u.id = c.id_ubicacion)));


ALTER TABLE public.lista_celula_lider_red_act OWNER TO postgres;

--
-- TOC entry 291 (class 1259 OID 19200)
-- Name: lista_celula_lider_red_des; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_celula_lider_red_des AS
    SELECT c.id, p.nombre, p.apellidos, 0 AS tip_lider, c.id_red, c.tipo, u.direccion, c.fecha_creacion, c.activo FROM ((celula c JOIN persona p ON (((p.id = c.id_lider_red) AND (c.activo = false)))) JOIN ubicacion u ON ((u.id = c.id_ubicacion)));


ALTER TABLE public.lista_celula_lider_red_des OWNER TO postgres;

--
-- TOC entry 287 (class 1259 OID 19149)
-- Name: lista_celula_misionero; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_celula_misionero AS
    SELECT c.id, p.nombre, p.apellidos, 1 AS tip_lider, c.id_red, c.tipo, u.direccion, c.fecha_creacion, c.activo FROM ((celula c JOIN persona p ON ((p.id = c.id_misionero))) JOIN ubicacion u ON ((u.id = c.id_ubicacion)));


ALTER TABLE public.lista_celula_misionero OWNER TO postgres;

--
-- TOC entry 293 (class 1259 OID 19210)
-- Name: lista_celula_misionero_act; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_celula_misionero_act AS
    SELECT c.id, p.nombre, p.apellidos, 1 AS tip_lider, c.id_red, c.tipo, u.direccion, c.fecha_creacion, c.activo FROM ((celula c JOIN persona p ON (((p.id = c.id_misionero) AND (c.activo = true)))) JOIN ubicacion u ON ((u.id = c.id_ubicacion)));


ALTER TABLE public.lista_celula_misionero_act OWNER TO postgres;

--
-- TOC entry 294 (class 1259 OID 19215)
-- Name: lista_celula_misionero_des; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_celula_misionero_des AS
    SELECT c.id, p.nombre, p.apellidos, 1 AS tip_lider, c.id_red, c.tipo, u.direccion, c.fecha_creacion, c.activo FROM ((celula c JOIN persona p ON (((p.id = c.id_misionero) AND (c.activo = false)))) JOIN ubicacion u ON ((u.id = c.id_ubicacion)));


ALTER TABLE public.lista_celula_misionero_des OWNER TO postgres;

--
-- TOC entry 288 (class 1259 OID 19154)
-- Name: lista_celula_pastor_eje; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_celula_pastor_eje AS
    SELECT c.id, p.nombre, p.apellidos, 2 AS tip_lider, c.id_red, c.tipo, u.direccion, c.fecha_creacion, c.activo FROM ((celula c JOIN persona p ON ((p.id = c.id_pastor_ejecutivo))) JOIN ubicacion u ON ((u.id = c.id_ubicacion)));


ALTER TABLE public.lista_celula_pastor_eje OWNER TO postgres;

--
-- TOC entry 295 (class 1259 OID 19220)
-- Name: lista_celula_pastor_eje_act; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_celula_pastor_eje_act AS
    SELECT c.id, p.nombre, p.apellidos, 2 AS tip_lider, c.id_red, c.tipo, u.direccion, c.fecha_creacion, c.activo FROM ((celula c JOIN persona p ON (((p.id = c.id_pastor_ejecutivo) AND (c.activo = true)))) JOIN ubicacion u ON ((u.id = c.id_ubicacion)));


ALTER TABLE public.lista_celula_pastor_eje_act OWNER TO postgres;

--
-- TOC entry 296 (class 1259 OID 19225)
-- Name: lista_celula_pastor_eje_des; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_celula_pastor_eje_des AS
    SELECT c.id, p.nombre, p.apellidos, 2 AS tip_lider, c.id_red, c.tipo, u.direccion, c.fecha_creacion, c.activo FROM ((celula c JOIN persona p ON (((p.id = c.id_pastor_ejecutivo) AND (c.activo = false)))) JOIN ubicacion u ON ((u.id = c.id_ubicacion)));


ALTER TABLE public.lista_celula_pastor_eje_des OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 16657)
-- Name: lista_celulas; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_celulas AS
    SELECT c.id, c.fecha_creacion, c.tipo, c.familia, c.telefono, c.id_ubicacion, c.id_red, c.id_misionero, c.id_pastor_ejecutivo, c.id_lider_red, c.activo, u.latitud, u.longitud, u.referencia FROM (celula c JOIN ubicacion u ON ((c.id_ubicacion = u.id)));


ALTER TABLE public.lista_celulas OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 16661)
-- Name: lista_consolidadores; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_consolidadores AS
    SELECT m.id, p.nombre, p.apellidos, p.edad, m.id_red AS red, m.id_celula AS celula, m.fecha_obtencion AS fecha, c.activo FROM ((consolidador c LEFT JOIN miembro m ON ((m.id = c.id))) LEFT JOIN persona p ON ((p.id = m.id)));


ALTER TABLE public.lista_consolidadores OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 16665)
-- Name: lista_consolidadores_act; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_consolidadores_act AS
    SELECT miembro.id, persona.nombre, persona.apellidos, persona.edad, miembro.id_red AS red, miembro.id_celula AS celula, miembro.fecha_obtencion AS fecha, consolidador.activo FROM ((consolidador LEFT JOIN miembro ON ((miembro.id = consolidador.id))) LEFT JOIN persona ON ((persona.id = miembro.id))) WHERE (consolidador.activo = true);


ALTER TABLE public.lista_consolidadores_act OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 16670)
-- Name: lista_descartar; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_descartar AS
    SELECT p.id, p.nombre AS nombres, p.apellidos, p.edad, m.id_red AS red, m.fecha_obtencion AS convertido, c.fecha_inicio AS inicio_leche, d.fecha_descarte AS descarte, d.cometario AS motivo FROM (((consolida c JOIN miembro m ON (((m.id = c.id_miembro) AND (c.pausa = true)))) JOIN persona p ON ((p.id = m.id))) JOIN descartado d ON ((d.id = p.id))) ORDER BY d.fecha_descarte DESC;


ALTER TABLE public.lista_descartar OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 16675)
-- Name: lista_lider_red_celula_con; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_lider_red_celula_con AS
    SELECT l.id, l.fecha_obtencion, l.fecha_fin, p.nombre, p.apellidos FROM (lider_red l JOIN persona p ON (((p.id = l.id) AND (l.activo = true))));


ALTER TABLE public.lista_lider_red_celula_con OWNER TO postgres;

--
-- TOC entry 2950 (class 0 OID 0)
-- Dependencies: 234
-- Name: VIEW lista_lider_red_celula_con; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON VIEW lista_lider_red_celula_con IS 'lista de lideres de red';


--
-- TOC entry 235 (class 1259 OID 16679)
-- Name: lista_lider_red_celula_sin; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_lider_red_celula_sin AS
    SELECT l.id, l.fecha_obtencion, l.fecha_fin, p.nombre, p.apellidos FROM (lider_red l JOIN persona p ON ((((p.id = l.id) AND (l.activo = true)) AND (NOT (EXISTS (SELECT 1 FROM celula r WHERE (r.id_lider_red = l.id)))))));


ALTER TABLE public.lista_lider_red_celula_sin OWNER TO postgres;

--
-- TOC entry 2951 (class 0 OID 0)
-- Dependencies: 235
-- Name: VIEW lista_lider_red_celula_sin; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON VIEW lista_lider_red_celula_sin IS 'lista de lideres de red sin asociar celula';


--
-- TOC entry 284 (class 1259 OID 19058)
-- Name: lista_lideres_celula_con; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_lideres_celula_con AS
    SELECT l.id, l.fecha_obtencion, l.fecha_fin, p.nombre, p.apellidos FROM (lider l JOIN persona p ON (((p.id = l.id) AND (l.activo = true))));


ALTER TABLE public.lista_lideres_celula_con OWNER TO postgres;

--
-- TOC entry 2952 (class 0 OID 0)
-- Dependencies: 284
-- Name: VIEW lista_lideres_celula_con; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON VIEW lista_lideres_celula_con IS 'lista de lideres';


--
-- TOC entry 283 (class 1259 OID 19052)
-- Name: lista_lideres_celula_sin; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_lideres_celula_sin AS
    SELECT l.id, l.fecha_obtencion, l.fecha_fin, p.nombre, p.apellidos FROM (lider l JOIN persona p ON ((((p.id = l.id) AND (l.activo = true)) AND (NOT (EXISTS (SELECT 1 FROM celula r WHERE (r.id_lider = l.id)))))));


ALTER TABLE public.lista_lideres_celula_sin OWNER TO postgres;

--
-- TOC entry 2953 (class 0 OID 0)
-- Dependencies: 283
-- Name: VIEW lista_lideres_celula_sin; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON VIEW lista_lideres_celula_sin IS 'lista de lideres';


--
-- TOC entry 236 (class 1259 OID 16684)
-- Name: red; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE red (
    id character varying(10) NOT NULL,
    tipo smallint DEFAULT 0 NOT NULL,
    id_ubicacion bigint NOT NULL,
    id_iglesia integer NOT NULL,
    id_lider_red bigint,
    id_pastor_asociado bigint,
    inicio date,
    activo boolean DEFAULT true
);


ALTER TABLE public.red OWNER TO postgres;

--
-- TOC entry 2954 (class 0 OID 0)
-- Dependencies: 236
-- Name: COLUMN red.tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN red.tipo IS '0 - mixta, 1- hombres, 2 - mujeres, 3 - hombres_jovenes, 4 - mujeres_jovenes';


--
-- TOC entry 2955 (class 0 OID 0)
-- Dependencies: 236
-- Name: COLUMN red.id_ubicacion; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN red.id_ubicacion IS 'Identificador de la Ubicación, en este caso es un entero autoincremental';


--
-- TOC entry 2956 (class 0 OID 0)
-- Dependencies: 236
-- Name: COLUMN red.id_iglesia; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN red.id_iglesia IS 'Identificador de la iglesia';


--
-- TOC entry 2957 (class 0 OID 0)
-- Dependencies: 236
-- Name: COLUMN red.id_lider_red; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN red.id_lider_red IS 'Identificador de la persona';


--
-- TOC entry 2958 (class 0 OID 0)
-- Dependencies: 236
-- Name: COLUMN red.id_pastor_asociado; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN red.id_pastor_asociado IS 'Identificador de la persona';


--
-- TOC entry 237 (class 1259 OID 16689)
-- Name: lista_lideres_red_sin; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_lideres_red_sin AS
    SELECT l.id, l.fecha_obtencion, l.fecha_fin, p.nombre, p.apellidos FROM (lider_red l JOIN persona p ON ((((p.id = l.id) AND (l.activo = true)) AND (NOT (EXISTS (SELECT 1 FROM red r WHERE (r.id_lider_red = l.id)))))));


ALTER TABLE public.lista_lideres_red_sin OWNER TO postgres;

--
-- TOC entry 2959 (class 0 OID 0)
-- Dependencies: 237
-- Name: VIEW lista_lideres_red_sin; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON VIEW lista_lideres_red_sin IS 'lista de lideres de red sin asociar';


--
-- TOC entry 238 (class 1259 OID 16694)
-- Name: misionero; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE misionero (
    fecha_obtencion date NOT NULL,
    activo boolean DEFAULT true,
    fecha_fin date,
    id bigint NOT NULL
);


ALTER TABLE public.misionero OWNER TO postgres;

--
-- TOC entry 2960 (class 0 OID 0)
-- Dependencies: 238
-- Name: COLUMN misionero.fecha_obtencion; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN misionero.fecha_obtencion IS 'Fecha en la que se hiso misionero';


--
-- TOC entry 2961 (class 0 OID 0)
-- Dependencies: 238
-- Name: COLUMN misionero.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN misionero.id IS 'Identificador de la persona';


--
-- TOC entry 239 (class 1259 OID 16698)
-- Name: lista_misionero_celula_con; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_misionero_celula_con AS
    SELECT l.id, l.fecha_obtencion, l.fecha_fin, p.nombre, p.apellidos FROM (misionero l JOIN persona p ON ((((p.id = l.id) AND (l.activo = true)) AND (NOT (EXISTS (SELECT 1 FROM celula r WHERE (r.id_misionero = l.id)))))));


ALTER TABLE public.lista_misionero_celula_con OWNER TO postgres;

--
-- TOC entry 2962 (class 0 OID 0)
-- Dependencies: 239
-- Name: VIEW lista_misionero_celula_con; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON VIEW lista_misionero_celula_con IS 'lista de lideres de misioneros ';


--
-- TOC entry 240 (class 1259 OID 16703)
-- Name: lista_misionero_celula_sin; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_misionero_celula_sin AS
    SELECT l.id, l.fecha_obtencion, l.fecha_fin, p.nombre, p.apellidos FROM (misionero l JOIN persona p ON ((((p.id = l.id) AND (l.activo = true)) AND (NOT (EXISTS (SELECT 1 FROM celula r WHERE (r.id_misionero = l.id)))))));


ALTER TABLE public.lista_misionero_celula_sin OWNER TO postgres;

--
-- TOC entry 2963 (class 0 OID 0)
-- Dependencies: 240
-- Name: VIEW lista_misionero_celula_sin; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON VIEW lista_misionero_celula_sin IS 'lista de lideres de misioneros sin asociar celula';


--
-- TOC entry 241 (class 1259 OID 16708)
-- Name: lista_nuevos_consolidadores_reg; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_nuevos_consolidadores_reg AS
    SELECT m.id, p.nombre, p.apellidos, p.edad, m.id_red AS red, m.id_celula AS celula, m.fecha_obtencion AS fecha FROM (miembro m JOIN persona p ON (((((p.id = m.id) AND (m.activo = true)) AND (m.apto_consolidar = true)) AND (NOT (EXISTS (SELECT 1 FROM consolidador c WHERE (c.id = m.id)))))));


ALTER TABLE public.lista_nuevos_consolidadores_reg OWNER TO postgres;

--
-- TOC entry 242 (class 1259 OID 16713)
-- Name: pastor_asociado; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE pastor_asociado (
    fecha_obtencion date NOT NULL,
    activo boolean DEFAULT true,
    fecha_fin date,
    id bigint NOT NULL
);


ALTER TABLE public.pastor_asociado OWNER TO postgres;

--
-- TOC entry 2964 (class 0 OID 0)
-- Dependencies: 242
-- Name: COLUMN pastor_asociado.fecha_obtencion; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pastor_asociado.fecha_obtencion IS 'Fecha en la que obtuvo el rol de pastor asociado';


--
-- TOC entry 2965 (class 0 OID 0)
-- Dependencies: 242
-- Name: COLUMN pastor_asociado.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pastor_asociado.id IS 'Identificador de la persona';


--
-- TOC entry 243 (class 1259 OID 16717)
-- Name: lista_pastor_asoc_sin; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_pastor_asoc_sin AS
    SELECT l.id, l.fecha_obtencion, l.fecha_fin, p.nombre, p.apellidos FROM (pastor_asociado l JOIN persona p ON ((((p.id = l.id) AND (l.activo = true)) AND (NOT (EXISTS (SELECT 1 FROM red r WHERE (r.id_pastor_asociado = l.id)))))));


ALTER TABLE public.lista_pastor_asoc_sin OWNER TO postgres;

--
-- TOC entry 2966 (class 0 OID 0)
-- Dependencies: 243
-- Name: VIEW lista_pastor_asoc_sin; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON VIEW lista_pastor_asoc_sin IS 'lista de pastores asociados sin ser asignados a una red ';


--
-- TOC entry 244 (class 1259 OID 16722)
-- Name: pastor_ejecutivo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE pastor_ejecutivo (
    fecha_obtencion date NOT NULL,
    activo boolean DEFAULT true,
    fecha_fin date,
    id bigint NOT NULL
);


ALTER TABLE public.pastor_ejecutivo OWNER TO postgres;

--
-- TOC entry 2967 (class 0 OID 0)
-- Dependencies: 244
-- Name: COLUMN pastor_ejecutivo.fecha_obtencion; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pastor_ejecutivo.fecha_obtencion IS 'Fecha en la que obtuvo el rol de pastor ejecutivo';


--
-- TOC entry 2968 (class 0 OID 0)
-- Dependencies: 244
-- Name: COLUMN pastor_ejecutivo.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN pastor_ejecutivo.id IS 'Identificador de la persona';


--
-- TOC entry 245 (class 1259 OID 16726)
-- Name: lista_pastor_eje_celula_con; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_pastor_eje_celula_con AS
    SELECT l.id, l.fecha_obtencion, l.fecha_fin, p.nombre, p.apellidos FROM (pastor_ejecutivo l JOIN persona p ON ((((p.id = l.id) AND (l.activo = true)) AND (NOT (EXISTS (SELECT 1 FROM celula r WHERE (r.id_misionero = l.id)))))));


ALTER TABLE public.lista_pastor_eje_celula_con OWNER TO postgres;

--
-- TOC entry 2969 (class 0 OID 0)
-- Dependencies: 245
-- Name: VIEW lista_pastor_eje_celula_con; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON VIEW lista_pastor_eje_celula_con IS 'lista de pastores ejecutivos';


--
-- TOC entry 246 (class 1259 OID 16731)
-- Name: lista_pastor_eje_celula_sin; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_pastor_eje_celula_sin AS
    SELECT l.id, l.fecha_obtencion, l.fecha_fin, p.nombre, p.apellidos FROM (pastor_ejecutivo l JOIN persona p ON ((((p.id = l.id) AND (l.activo = true)) AND (NOT (EXISTS (SELECT 1 FROM celula r WHERE (r.id_misionero = l.id)))))));


ALTER TABLE public.lista_pastor_eje_celula_sin OWNER TO postgres;

--
-- TOC entry 2970 (class 0 OID 0)
-- Dependencies: 246
-- Name: VIEW lista_pastor_eje_celula_sin; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON VIEW lista_pastor_eje_celula_sin IS 'lista de pastores ejecutivos sin asociar celula';


--
-- TOC entry 247 (class 1259 OID 16736)
-- Name: lista_red_celula_sin; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_red_celula_sin AS
    SELECT l.id, l.fecha_obtencion, l.fecha_fin, p.nombre, p.apellidos FROM (lider_red l JOIN persona p ON ((((p.id = l.id) AND (l.activo = true)) AND (NOT (EXISTS (SELECT 1 FROM celula r WHERE (r.id_lider_red = l.id)))))));


ALTER TABLE public.lista_red_celula_sin OWNER TO postgres;

--
-- TOC entry 2971 (class 0 OID 0)
-- Dependencies: 247
-- Name: VIEW lista_red_celula_sin; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON VIEW lista_red_celula_sin IS 'lista de lideres de red sin asociar celula';


--
-- TOC entry 282 (class 1259 OID 19019)
-- Name: lista_red_ubicacion; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_red_ubicacion AS
    SELECT r.id, r.tipo, CASE WHEN (r.id_lider_red IS NOT NULL) THEN (SELECT pg_catalog.concat(p.nombre, ' ', p.apellidos) AS concat FROM persona p WHERE (p.id = r.id_lider_red)) WHEN (r.id_pastor_asociado IS NOT NULL) THEN (SELECT pg_catalog.concat(p.nombre, ' ', p.apellidos) AS concat FROM persona p WHERE (p.id = r.id_pastor_asociado)) ELSE NULL::text END AS nombres, r.inicio, u.direccion FROM (red r JOIN ubicacion u ON (((u.id = r.id_ubicacion) AND (r.activo = true))));


ALTER TABLE public.lista_red_ubicacion OWNER TO postgres;

--
-- TOC entry 248 (class 1259 OID 16746)
-- Name: lista_redes; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_redes AS
    SELECT r.id, r.tipo, p.nombre, p.apellidos FROM ((red r JOIN lider_red l ON (((l.id = r.id_lider_red) AND (l.activo = true)))) JOIN persona p ON ((p.id = r.id_lider_red)));


ALTER TABLE public.lista_redes OWNER TO postgres;

--
-- TOC entry 249 (class 1259 OID 16751)
-- Name: ubigeo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE ubigeo (
    id integer NOT NULL,
    coddepartamento smallint,
    codprovincia smallint,
    coddistrito smallint,
    departamento character varying(70),
    provincia character varying(70),
    distrito character varying(70),
    lat double precision,
    long double precision
);


ALTER TABLE public.ubigeo OWNER TO postgres;

--
-- TOC entry 250 (class 1259 OID 16754)
-- Name: lista_regiones; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_regiones AS
    SELECT u.coddepartamento, u.departamento, u.lat, u.long FROM ubigeo u GROUP BY u.coddepartamento, u.departamento, u.lat, u.long;


ALTER TABLE public.lista_regiones OWNER TO postgres;

--
-- TOC entry 251 (class 1259 OID 16758)
-- Name: tema_celula; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tema_celula (
    id integer NOT NULL,
    titutlo character varying(100) NOT NULL,
    autor character varying(70) NOT NULL,
    descripcion text NOT NULL,
    fecha date,
    tipo character varying(20)
);


ALTER TABLE public.tema_celula OWNER TO postgres;

--
-- TOC entry 252 (class 1259 OID 16764)
-- Name: lista_tema_celula; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW lista_tema_celula AS
    SELECT tema_celula.id, tema_celula.titutlo AS titulo, tema_celula.fecha, tema_celula.autor, tema_celula.tipo, tema_celula.descripcion FROM tema_celula ORDER BY tema_celula.fecha DESC;


ALTER TABLE public.lista_tema_celula OWNER TO postgres;

--
-- TOC entry 253 (class 1259 OID 16768)
-- Name: local; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE local (
    codigo character(2) NOT NULL,
    nombe character varying(20) NOT NULL,
    tipo smallint NOT NULL,
    id bigint NOT NULL
);


ALTER TABLE public.local OWNER TO postgres;

--
-- TOC entry 2972 (class 0 OID 0)
-- Dependencies: 253
-- Name: COLUMN local.tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN local.tipo IS 'Tipo de local: Aula, Auditorio, centro computo, etc';


--
-- TOC entry 254 (class 1259 OID 16771)
-- Name: local_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE local_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.local_id_seq OWNER TO postgres;

--
-- TOC entry 2973 (class 0 OID 0)
-- Dependencies: 254
-- Name: local_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE local_id_seq OWNED BY local.id;


--
-- TOC entry 255 (class 1259 OID 16773)
-- Name: lugar; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE lugar (
    id integer NOT NULL,
    nombre character varying(50) NOT NULL
);


ALTER TABLE public.lugar OWNER TO postgres;

--
-- TOC entry 2974 (class 0 OID 0)
-- Dependencies: 255
-- Name: TABLE lugar; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE lugar IS 'Tabla que almacena la lista de lugares en los que se gana un alma al enemigo.';


--
-- TOC entry 256 (class 1259 OID 16776)
-- Name: lugar_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE lugar_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.lugar_id_seq OWNER TO postgres;

--
-- TOC entry 2975 (class 0 OID 0)
-- Dependencies: 256
-- Name: lugar_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE lugar_id_seq OWNED BY lugar.id;


SET default_with_oids = true;

--
-- TOC entry 257 (class 1259 OID 16778)
-- Name: many_clase_celula_has_many_miembro; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE many_clase_celula_has_many_miembro (
    id_clase_cell bigint NOT NULL,
    id_miembro bigint NOT NULL,
    estado boolean DEFAULT false
);


ALTER TABLE public.many_clase_celula_has_many_miembro OWNER TO postgres;

--
-- TOC entry 2976 (class 0 OID 0)
-- Dependencies: 257
-- Name: COLUMN many_clase_celula_has_many_miembro.id_miembro; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN many_clase_celula_has_many_miembro.id_miembro IS 'Identificador de la persona';


--
-- TOC entry 258 (class 1259 OID 16782)
-- Name: many_consolidacion_has_many_tema_leche; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE many_consolidacion_has_many_tema_leche (
    id_consolida bigint NOT NULL,
    id_tema_leche integer NOT NULL,
    fecha_hora_inicio timestamp without time zone,
    fecha_hora_fin timestamp without time zone,
    fecha_hora_limite timestamp without time zone
);


ALTER TABLE public.many_consolidacion_has_many_tema_leche OWNER TO postgres;

--
-- TOC entry 259 (class 1259 OID 16785)
-- Name: many_encargado_has_many_area_vision; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE many_encargado_has_many_area_vision (
    id_encargado bigint NOT NULL,
    id_area_vision character(2) NOT NULL
);


ALTER TABLE public.many_encargado_has_many_area_vision OWNER TO postgres;

--
-- TOC entry 2977 (class 0 OID 0)
-- Dependencies: 259
-- Name: COLUMN many_encargado_has_many_area_vision.id_encargado; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN many_encargado_has_many_area_vision.id_encargado IS 'Identificador de la persona';


--
-- TOC entry 260 (class 1259 OID 16788)
-- Name: many_herramienta_has_many_consolidacion; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE many_herramienta_has_many_consolidacion (
    id_herramienta integer NOT NULL,
    id_consolida bigint NOT NULL,
    fecha_hora_aplicacion timestamp without time zone,
    fecha_hora_propuesta timestamp without time zone,
    hecho boolean DEFAULT false
);


ALTER TABLE public.many_herramienta_has_many_consolidacion OWNER TO postgres;

--
-- TOC entry 2978 (class 0 OID 0)
-- Dependencies: 260
-- Name: COLUMN many_herramienta_has_many_consolidacion.id_herramienta; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN many_herramienta_has_many_consolidacion.id_herramienta IS 'Identificador de la herramienta. Es entero y autoincremental.';


--
-- TOC entry 261 (class 1259 OID 16792)
-- Name: many_usuario_has_many_rol; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE many_usuario_has_many_rol (
    id_usuario bigint NOT NULL,
    id_rol integer NOT NULL
);


ALTER TABLE public.many_usuario_has_many_rol OWNER TO postgres;

--
-- TOC entry 2979 (class 0 OID 0)
-- Dependencies: 261
-- Name: COLUMN many_usuario_has_many_rol.id_usuario; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN many_usuario_has_many_rol.id_usuario IS 'Identificador de usuario';


SET default_with_oids = false;

--
-- TOC entry 262 (class 1259 OID 16795)
-- Name: matric; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE matric (
    id bigint NOT NULL,
    fecha date,
    activo boolean DEFAULT true,
    id_persona_estudiante bigint NOT NULL,
    id_curso_impartido bigint NOT NULL
);


ALTER TABLE public.matric OWNER TO postgres;

--
-- TOC entry 2980 (class 0 OID 0)
-- Dependencies: 262
-- Name: COLUMN matric.id_persona_estudiante; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN matric.id_persona_estudiante IS 'Identificador de la persona';


--
-- TOC entry 263 (class 1259 OID 16799)
-- Name: matric_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE matric_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.matric_id_seq OWNER TO postgres;

--
-- TOC entry 2981 (class 0 OID 0)
-- Dependencies: 263
-- Name: matric_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE matric_id_seq OWNED BY matric.id;


--
-- TOC entry 264 (class 1259 OID 16801)
-- Name: nuevo_convertido; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE nuevo_convertido (
    fecha_conversion date NOT NULL,
    peticion text NOT NULL,
    consolidado boolean DEFAULT false,
    id bigint NOT NULL,
    id_celula bigint,
    id_red character varying(10),
    id_lugar integer NOT NULL
);


ALTER TABLE public.nuevo_convertido OWNER TO postgres;

--
-- TOC entry 2982 (class 0 OID 0)
-- Dependencies: 264
-- Name: TABLE nuevo_convertido; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE nuevo_convertido IS 'Tabla que guarda los datos del consolidando';


--
-- TOC entry 2983 (class 0 OID 0)
-- Dependencies: 264
-- Name: COLUMN nuevo_convertido.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN nuevo_convertido.id IS 'Identificador de la persona';


--
-- TOC entry 265 (class 1259 OID 16808)
-- Name: nuevos_convertidos; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW nuevos_convertidos AS
    SELECT persona.id, persona.nombre AS nombres, persona.apellidos, persona.edad, nuevo_convertido.id_red AS red, nuevo_convertido.id_celula AS celula, nuevo_convertido.fecha_conversion AS conversion FROM (nuevo_convertido JOIN persona ON ((nuevo_convertido.id = persona.id))) WHERE (nuevo_convertido.consolidado = false);


ALTER TABLE public.nuevos_convertidos OWNER TO postgres;

--
-- TOC entry 266 (class 1259 OID 16812)
-- Name: persona_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE persona_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.persona_id_seq OWNER TO postgres;

--
-- TOC entry 2984 (class 0 OID 0)
-- Dependencies: 266
-- Name: persona_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE persona_id_seq OWNED BY persona.id;


--
-- TOC entry 267 (class 1259 OID 16814)
-- Name: prerequisito; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE prerequisito (
    id_curso bigint NOT NULL,
    id_curso1 bigint NOT NULL,
    id_curso2 bigint NOT NULL
);


ALTER TABLE public.prerequisito OWNER TO postgres;

--
-- TOC entry 268 (class 1259 OID 16817)
-- Name: prerequisito_id_curso_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE prerequisito_id_curso_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.prerequisito_id_curso_seq OWNER TO postgres;

--
-- TOC entry 2985 (class 0 OID 0)
-- Dependencies: 268
-- Name: prerequisito_id_curso_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE prerequisito_id_curso_seq OWNED BY prerequisito.id_curso;


--
-- TOC entry 269 (class 1259 OID 16819)
-- Name: red_social; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE red_social (
    id bigint NOT NULL,
    enlace character varying(100) NOT NULL,
    tipo character varying(30) NOT NULL,
    id_persona bigint NOT NULL
);


ALTER TABLE public.red_social OWNER TO postgres;

--
-- TOC entry 2986 (class 0 OID 0)
-- Dependencies: 269
-- Name: TABLE red_social; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE red_social IS 'Tabla que almacena los enlaces a las redes sociales en las que la persona se ha inscrito';


--
-- TOC entry 2987 (class 0 OID 0)
-- Dependencies: 269
-- Name: COLUMN red_social.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN red_social.id IS 'Identificador de la red social. es un entero autoincremental';


--
-- TOC entry 2988 (class 0 OID 0)
-- Dependencies: 269
-- Name: COLUMN red_social.enlace; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN red_social.enlace IS 'Direccion web a la portada que tiene la persona en dicha red social.';


--
-- TOC entry 2989 (class 0 OID 0)
-- Dependencies: 269
-- Name: COLUMN red_social.tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN red_social.tipo IS 'Es el nombre de la red social.';


--
-- TOC entry 2990 (class 0 OID 0)
-- Dependencies: 269
-- Name: COLUMN red_social.id_persona; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN red_social.id_persona IS 'Identificador de la persona';


--
-- TOC entry 270 (class 1259 OID 16822)
-- Name: red_social_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE red_social_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.red_social_id_seq OWNER TO postgres;

--
-- TOC entry 2991 (class 0 OID 0)
-- Dependencies: 270
-- Name: red_social_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE red_social_id_seq OWNED BY red_social.id;


--
-- TOC entry 271 (class 1259 OID 16824)
-- Name: rol; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE rol (
    id integer NOT NULL,
    nombre character varying(255) NOT NULL
);


ALTER TABLE public.rol OWNER TO postgres;

--
-- TOC entry 272 (class 1259 OID 16827)
-- Name: rol_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE rol_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.rol_id_seq OWNER TO postgres;

--
-- TOC entry 2992 (class 0 OID 0)
-- Dependencies: 272
-- Name: rol_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE rol_id_seq OWNED BY rol.id;


--
-- TOC entry 273 (class 1259 OID 16829)
-- Name: tema_celula_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tema_celula_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tema_celula_id_seq OWNER TO postgres;

--
-- TOC entry 2993 (class 0 OID 0)
-- Dependencies: 273
-- Name: tema_celula_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tema_celula_id_seq OWNED BY tema_celula.id;


--
-- TOC entry 274 (class 1259 OID 16831)
-- Name: tema_curso; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tema_curso (
    id integer NOT NULL,
    activo boolean DEFAULT true NOT NULL,
    fecha_creacion date NOT NULL,
    descripcion text NOT NULL,
    tipo smallint NOT NULL,
    id_curso bigint NOT NULL
);


ALTER TABLE public.tema_curso OWNER TO postgres;

--
-- TOC entry 2994 (class 0 OID 0)
-- Dependencies: 274
-- Name: COLUMN tema_curso.tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN tema_curso.tipo IS 'define si el tema es Sesion o es Extra.';


--
-- TOC entry 275 (class 1259 OID 16838)
-- Name: tema_curso_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tema_curso_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tema_curso_id_seq OWNER TO postgres;

--
-- TOC entry 2995 (class 0 OID 0)
-- Dependencies: 275
-- Name: tema_curso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tema_curso_id_seq OWNED BY tema_curso.id;


--
-- TOC entry 276 (class 1259 OID 16840)
-- Name: tema_leche; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tema_leche (
    id integer NOT NULL,
    titulo character varying(100),
    id_leche_espiritual integer NOT NULL
);


ALTER TABLE public.tema_leche OWNER TO postgres;

--
-- TOC entry 2996 (class 0 OID 0)
-- Dependencies: 276
-- Name: COLUMN tema_leche.id_leche_espiritual; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN tema_leche.id_leche_espiritual IS 'Identificador de la clase leche';


--
-- TOC entry 277 (class 1259 OID 16843)
-- Name: tema_leche_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tema_leche_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tema_leche_id_seq OWNER TO postgres;

--
-- TOC entry 2997 (class 0 OID 0)
-- Dependencies: 277
-- Name: tema_leche_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tema_leche_id_seq OWNED BY tema_leche.id;


--
-- TOC entry 278 (class 1259 OID 16845)
-- Name: ubicacion_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ubicacion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ubicacion_id_seq OWNER TO postgres;

--
-- TOC entry 2998 (class 0 OID 0)
-- Dependencies: 278
-- Name: ubicacion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE ubicacion_id_seq OWNED BY ubicacion.id;


--
-- TOC entry 279 (class 1259 OID 16847)
-- Name: ubigeo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ubigeo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ubigeo_id_seq OWNER TO postgres;

--
-- TOC entry 2999 (class 0 OID 0)
-- Dependencies: 279
-- Name: ubigeo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE ubigeo_id_seq OWNED BY ubigeo.id;


SET default_with_oids = true;

--
-- TOC entry 280 (class 1259 OID 16849)
-- Name: usuario; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuario (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    salt character varying(255),
    id_persona bigint
);


ALTER TABLE public.usuario OWNER TO postgres;

--
-- TOC entry 3000 (class 0 OID 0)
-- Dependencies: 280
-- Name: TABLE usuario; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE usuario IS 'Tabla que almacena el usuario de una persoina';


--
-- TOC entry 3001 (class 0 OID 0)
-- Dependencies: 280
-- Name: COLUMN usuario.id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN usuario.id IS 'Identificador de usuario';


--
-- TOC entry 3002 (class 0 OID 0)
-- Dependencies: 280
-- Name: COLUMN usuario.nombre; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN usuario.nombre IS 'Nombre de usuario';


--
-- TOC entry 3003 (class 0 OID 0)
-- Dependencies: 280
-- Name: COLUMN usuario.password; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN usuario.password IS 'Contraseña del usuario, encriptada con MD5';


--
-- TOC entry 3004 (class 0 OID 0)
-- Dependencies: 280
-- Name: COLUMN usuario.id_persona; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN usuario.id_persona IS 'Identificador de la persona';


--
-- TOC entry 281 (class 1259 OID 16855)
-- Name: usuario_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE usuario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuario_id_seq OWNER TO postgres;

--
-- TOC entry 3005 (class 0 OID 0)
-- Dependencies: 281
-- Name: usuario_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE usuario_id_seq OWNED BY usuario.id;


--
-- TOC entry 2499 (class 2604 OID 16857)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY archivo ALTER COLUMN id SET DEFAULT nextval('archivo_id_seq'::regclass);


--
-- TOC entry 2503 (class 2604 OID 16858)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY celula ALTER COLUMN id SET DEFAULT nextval('celula_id_seq'::regclass);


--
-- TOC entry 2504 (class 2604 OID 16859)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY clase_cell ALTER COLUMN id SET DEFAULT nextval('clase_cell_id_seq'::regclass);


--
-- TOC entry 2505 (class 2604 OID 16860)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY clase_curso ALTER COLUMN id SET DEFAULT nextval('clase_curso_id_seq'::regclass);


--
-- TOC entry 2508 (class 2604 OID 16861)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY consolida ALTER COLUMN id SET DEFAULT nextval('consolida_id_seq'::regclass);


--
-- TOC entry 2513 (class 2604 OID 16862)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY criterio_evaluacion ALTER COLUMN id SET DEFAULT nextval('criterio_evaluacion_id_seq'::regclass);


--
-- TOC entry 2515 (class 2604 OID 16863)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY curso ALTER COLUMN id SET DEFAULT nextval('curso_id_seq'::regclass);


--
-- TOC entry 2517 (class 2604 OID 16864)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY curso_impartido ALTER COLUMN id SET DEFAULT nextval('curso_impartido_id_seq'::regclass);


--
-- TOC entry 2521 (class 2604 OID 16865)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento ALTER COLUMN id SET DEFAULT nextval('evento_id_seq'::regclass);


--
-- TOC entry 2522 (class 2604 OID 16866)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_realizado ALTER COLUMN id SET DEFAULT nextval('evento_realizado_id_seq'::regclass);


--
-- TOC entry 2523 (class 2604 OID 16867)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY herramienta ALTER COLUMN id SET DEFAULT nextval('herramienta_id_seq'::regclass);


--
-- TOC entry 2524 (class 2604 OID 16868)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY horario ALTER COLUMN id SET DEFAULT nextval('horario_id_seq'::regclass);


--
-- TOC entry 2525 (class 2604 OID 16869)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY iglesia ALTER COLUMN id SET DEFAULT nextval('iglesia_id_seq'::regclass);


--
-- TOC entry 2526 (class 2604 OID 16870)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY informe ALTER COLUMN id SET DEFAULT nextval('informe_id_seq'::regclass);


--
-- TOC entry 2527 (class 2604 OID 16871)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY leche_espiritual ALTER COLUMN id SET DEFAULT nextval('leche_espiritual_id_seq'::regclass);


--
-- TOC entry 2538 (class 2604 OID 16872)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY local ALTER COLUMN id SET DEFAULT nextval('local_id_seq'::regclass);


--
-- TOC entry 2539 (class 2604 OID 16873)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY lugar ALTER COLUMN id SET DEFAULT nextval('lugar_id_seq'::regclass);


--
-- TOC entry 2543 (class 2604 OID 16874)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY matric ALTER COLUMN id SET DEFAULT nextval('matric_id_seq'::regclass);


--
-- TOC entry 2511 (class 2604 OID 16875)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY persona ALTER COLUMN id SET DEFAULT nextval('persona_id_seq'::regclass);


--
-- TOC entry 2545 (class 2604 OID 16876)
-- Name: id_curso; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY prerequisito ALTER COLUMN id_curso SET DEFAULT nextval('prerequisito_id_curso_seq'::regclass);


--
-- TOC entry 2546 (class 2604 OID 16877)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY red_social ALTER COLUMN id SET DEFAULT nextval('red_social_id_seq'::regclass);


--
-- TOC entry 2547 (class 2604 OID 16878)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rol ALTER COLUMN id SET DEFAULT nextval('rol_id_seq'::regclass);


--
-- TOC entry 2537 (class 2604 OID 16879)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tema_celula ALTER COLUMN id SET DEFAULT nextval('tema_celula_id_seq'::regclass);


--
-- TOC entry 2549 (class 2604 OID 16880)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tema_curso ALTER COLUMN id SET DEFAULT nextval('tema_curso_id_seq'::regclass);


--
-- TOC entry 2550 (class 2604 OID 16881)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tema_leche ALTER COLUMN id SET DEFAULT nextval('tema_leche_id_seq'::regclass);


--
-- TOC entry 2530 (class 2604 OID 16882)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ubicacion ALTER COLUMN id SET DEFAULT nextval('ubicacion_id_seq'::regclass);


--
-- TOC entry 2536 (class 2604 OID 16883)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ubigeo ALTER COLUMN id SET DEFAULT nextval('ubigeo_id_seq'::regclass);


--
-- TOC entry 2551 (class 2604 OID 16884)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario ALTER COLUMN id SET DEFAULT nextval('usuario_id_seq'::regclass);


--
-- TOC entry 2775 (class 0 OID 16449)
-- Dependencies: 178
-- Data for Name: archivo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY archivo (id, direccion, peso, tipo, extension, nombre, fecha, id_tema_celula, id_tema_curso, id_leche_espiritual) FROM stdin;
28	uploads/2013-05-14-14-47-49-arquitectura.png	\N	\N	\N	2013-05-14-14-47-49-arquitectura.png	2013-05-14	11	\N	\N
29	uploads/2013-05-14-15-50-14StyleTable.doc	\N	\N	\N	\N	2013-05-14	\N	\N	23
\.


--
-- TOC entry 3006 (class 0 OID 0)
-- Dependencies: 179
-- Name: archivo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('archivo_id_seq', 29, true);


--
-- TOC entry 2777 (class 0 OID 16457)
-- Dependencies: 180
-- Data for Name: area_vision; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY area_vision (id, nombre) FROM stdin;
\.


--
-- TOC entry 2778 (class 0 OID 16460)
-- Dependencies: 181
-- Data for Name: asistencia_clase_curso; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY asistencia_clase_curso (id_persona_estudiante, id_clase_curso, nota, asistencia) FROM stdin;
\.


--
-- TOC entry 2779 (class 0 OID 16465)
-- Dependencies: 182
-- Data for Name: celula; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY celula (id, fecha_creacion, tipo, familia, telefono, id_ubicacion, id_red, id_misionero, id_pastor_ejecutivo, id_lider_red, activo, id_lider) FROM stdin;
15	2013-05-14	0	UNT		201	MJ3	\N	\N	\N	t	79
16	2013-05-14	0	UNT		204	HJ3	\N	\N	81	t	\N
17	2013-05-14	0	Saulo Montoya		205	H4	\N	\N	83	t	\N
\.


--
-- TOC entry 3007 (class 0 OID 0)
-- Dependencies: 185
-- Name: celula_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('celula_id_seq', 17, true);


--
-- TOC entry 2781 (class 0 OID 16479)
-- Dependencies: 186
-- Data for Name: clase_cell; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY clase_cell (ofrenda, fecha_dicto, id, id_horario, id_celula, id_tema_celula) FROM stdin;
\.


--
-- TOC entry 3008 (class 0 OID 0)
-- Dependencies: 187
-- Name: clase_cell_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('clase_cell_id_seq', 81, true);


--
-- TOC entry 2783 (class 0 OID 16484)
-- Dependencies: 188
-- Data for Name: clase_curso; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY clase_curso (id, fecha_dicto, id_curso_impartido, tema) FROM stdin;
\.


--
-- TOC entry 3009 (class 0 OID 0)
-- Dependencies: 189
-- Name: clase_curso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('clase_curso_id_seq', 13, true);


--
-- TOC entry 2785 (class 0 OID 16489)
-- Dependencies: 190
-- Data for Name: consolida; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY consolida (fecha_inicio, fecha_fin, pausa, fecha_pausa, fecha_reanudacion, id, termino, id_nuevo_convertido, id_miembro, id_consolidador) FROM stdin;
2013-05-14	2013-05-14	f	\N	\N	92	t	81	81	81
2013-05-14	2013-07-09	f	\N	\N	93	f	97	97	81
2013-05-14	2013-05-14	f	\N	\N	94	t	83	83	83
2013-05-14	2013-05-14	f	\N	\N	95	t	87	87	87
2013-05-14	2013-07-09	f	2013-05-14	2013-05-14	91	f	96	96	79
\.


--
-- TOC entry 3010 (class 0 OID 0)
-- Dependencies: 191
-- Name: consolida_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('consolida_id_seq', 95, true);


--
-- TOC entry 2789 (class 0 OID 16512)
-- Dependencies: 195
-- Data for Name: consolidador; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY consolidador (fecha_obtencion, activo, fecha_fin, id) FROM stdin;
2013-05-13	t	\N	83
2013-05-14	t	\N	79
2013-05-13	t	\N	81
2013-05-14	t	\N	87
\.


--
-- TOC entry 2790 (class 0 OID 16526)
-- Dependencies: 198
-- Data for Name: criterio_evaluacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY criterio_evaluacion (id, id_curso_impartido) FROM stdin;
\.


--
-- TOC entry 3011 (class 0 OID 0)
-- Dependencies: 199
-- Name: criterio_evaluacion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('criterio_evaluacion_id_seq', 1, false);


--
-- TOC entry 2792 (class 0 OID 16531)
-- Dependencies: 200
-- Data for Name: curso; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY curso (id, fecha_creacion, descripcion, numero_sesiones, activo, titulo) FROM stdin;
\.


--
-- TOC entry 3012 (class 0 OID 0)
-- Dependencies: 201
-- Name: curso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('curso_id_seq', 5, true);


--
-- TOC entry 2794 (class 0 OID 16540)
-- Dependencies: 202
-- Data for Name: curso_impartido; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY curso_impartido (fecha_creacion, id, id_curso, id_persona_docente, id_horario, activo, estado_matricula, fecha_inicio, fecha_fin, id_local) FROM stdin;
\.


--
-- TOC entry 3013 (class 0 OID 0)
-- Dependencies: 203
-- Name: curso_impartido_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('curso_impartido_id_seq', 6, true);


--
-- TOC entry 2796 (class 0 OID 16546)
-- Dependencies: 204
-- Data for Name: descartado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY descartado (cometario, fecha_descarte, id) FROM stdin;
 se fue de viaje	2013-05-14	96
\.


--
-- TOC entry 2797 (class 0 OID 16557)
-- Dependencies: 206
-- Data for Name: docente; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY docente (fecha_inicio, descripcion, activo, fecha_fin, id_persona) FROM stdin;
\.


--
-- TOC entry 2798 (class 0 OID 16568)
-- Dependencies: 208
-- Data for Name: encargado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY encargado (fecha_obtencion, activo, fecha_fin, id) FROM stdin;
\.


--
-- TOC entry 2799 (class 0 OID 16572)
-- Dependencies: 209
-- Data for Name: estudiante; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY estudiante (fecha_inicio, activo, fecha_fin, id) FROM stdin;
\.


--
-- TOC entry 2800 (class 0 OID 16576)
-- Dependencies: 210
-- Data for Name: evaluacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY evaluacion (id_persona_estudiante, id_criterio_evaluacion) FROM stdin;
\.


--
-- TOC entry 2801 (class 0 OID 16579)
-- Dependencies: 211
-- Data for Name: evento; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY evento (id, nombre, descripcion, "fechaIni", "fechaFin", id_ubicacion) FROM stdin;
\.


--
-- TOC entry 3014 (class 0 OID 0)
-- Dependencies: 212
-- Name: evento_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('evento_id_seq', 1, false);


--
-- TOC entry 2803 (class 0 OID 16587)
-- Dependencies: 213
-- Data for Name: evento_realizado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY evento_realizado (id, id_persona, id_evento) FROM stdin;
\.


--
-- TOC entry 3015 (class 0 OID 0)
-- Dependencies: 214
-- Name: evento_realizado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('evento_realizado_id_seq', 1, false);


--
-- TOC entry 2805 (class 0 OID 16592)
-- Dependencies: 215
-- Data for Name: herramienta; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY herramienta (id, nombre, tiempo_optimo) FROM stdin;
4	Contacto	2 days
5	Visita	7 days
6	Iglesia	14 days
7	Célula	14 days
10	Encuentro	50 days
\.


--
-- TOC entry 3016 (class 0 OID 0)
-- Dependencies: 216
-- Name: herramienta_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('herramienta_id_seq', 10, true);


--
-- TOC entry 2807 (class 0 OID 16597)
-- Dependencies: 217
-- Data for Name: horario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY horario (id, dia, hora_inicio, hora_fin) FROM stdin;
\.


--
-- TOC entry 3017 (class 0 OID 0)
-- Dependencies: 218
-- Name: horario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('horario_id_seq', 67, true);


--
-- TOC entry 2809 (class 0 OID 16602)
-- Dependencies: 219
-- Data for Name: iglesia; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY iglesia (id, nombre, telefono, id_ubicacion) FROM stdin;
11	CLM Trujillo	044234561	134
\.


--
-- TOC entry 2810 (class 0 OID 16605)
-- Dependencies: 220
-- Data for Name: iglesia_area; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY iglesia_area (id_iglesia, id_area_vision) FROM stdin;
\.


--
-- TOC entry 3018 (class 0 OID 0)
-- Dependencies: 221
-- Name: iglesia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('iglesia_id_seq', 11, true);


--
-- TOC entry 2812 (class 0 OID 16610)
-- Dependencies: 222
-- Data for Name: informe; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY informe (id, id_lider_red_receptor, id_pastor_asociado_receptor, id_encargado_receptor, id_misionero_receptor, fecha, id_lider_red, id_pastor_asociado, id_misionero, id_encargado, id_lider, id_pastor_ejecutivo) FROM stdin;
\.


--
-- TOC entry 3019 (class 0 OID 0)
-- Dependencies: 223
-- Name: informe_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('informe_id_seq', 1, false);


--
-- TOC entry 2814 (class 0 OID 16615)
-- Dependencies: 224
-- Data for Name: leche_espiritual; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY leche_espiritual (id, nombre, resumen, fecha_creacion) FROM stdin;
23	Leche Espiritual	En este libro usted podrá fortalecer al nuevo creyente 	2013-05-14
\.


--
-- TOC entry 3020 (class 0 OID 0)
-- Dependencies: 225
-- Name: leche_espiritual_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('leche_espiritual_id_seq', 23, true);


--
-- TOC entry 2816 (class 0 OID 16623)
-- Dependencies: 226
-- Data for Name: lider; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY lider (fecha_obtencion, activo, fecha_fin, id) FROM stdin;
2013-05-13	t	\N	83
2013-05-13	t	\N	81
2013-05-14	t	\N	79
\.


--
-- TOC entry 2817 (class 0 OID 16627)
-- Dependencies: 227
-- Data for Name: lider_red; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY lider_red (fecha_obtencion, activo, fecha_fin, id) FROM stdin;
2013-05-13	t	\N	83
2013-05-13	t	\N	81
2013-05-14	t	\N	79
2013-05-14	t	\N	87
\.


--
-- TOC entry 2825 (class 0 OID 16768)
-- Dependencies: 253
-- Data for Name: local; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY local (codigo, nombe, tipo, id) FROM stdin;
\.


--
-- TOC entry 3021 (class 0 OID 0)
-- Dependencies: 254
-- Name: local_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('local_id_seq', 1, true);


--
-- TOC entry 2827 (class 0 OID 16773)
-- Dependencies: 255
-- Data for Name: lugar; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY lugar (id, nombre) FROM stdin;
1	Evangelismo
2	Células
3	Cena
4	Fiesta
5	Invasión
6	Cultos
7	Otros
\.


--
-- TOC entry 3022 (class 0 OID 0)
-- Dependencies: 256
-- Name: lugar_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('lugar_id_seq', 7, true);


--
-- TOC entry 2829 (class 0 OID 16778)
-- Dependencies: 257
-- Data for Name: many_clase_celula_has_many_miembro; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY many_clase_celula_has_many_miembro (id_clase_cell, id_miembro, estado) FROM stdin;
\.


--
-- TOC entry 2830 (class 0 OID 16782)
-- Dependencies: 258
-- Data for Name: many_consolidacion_has_many_tema_leche; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY many_consolidacion_has_many_tema_leche (id_consolida, id_tema_leche, fecha_hora_inicio, fecha_hora_fin, fecha_hora_limite) FROM stdin;
91	19	2013-05-22 00:00:00	\N	2013-05-28 23:59:00
91	20	2013-05-30 00:00:00	\N	2013-06-05 23:59:00
91	21	2013-06-07 00:00:00	\N	2013-06-13 23:59:00
91	22	2013-06-15 00:00:00	\N	2013-06-21 23:59:00
91	23	2013-06-23 00:00:00	\N	2013-06-29 23:59:00
91	24	2013-07-01 00:00:00	\N	2013-07-07 23:59:00
91	25	2013-07-09 00:00:00	\N	2013-07-15 23:59:00
92	18	2013-05-14 00:00:00	2013-05-14 00:00:00	2013-05-20 23:59:00
92	19	2013-05-22 00:00:00	2013-05-14 00:00:00	2013-05-28 23:59:00
92	20	2013-05-30 00:00:00	2013-05-14 00:00:00	2013-06-05 23:59:00
92	21	2013-06-07 00:00:00	2013-05-14 00:00:00	2013-06-13 23:59:00
92	22	2013-06-15 00:00:00	2013-05-14 00:00:00	2013-06-21 23:59:00
92	23	2013-06-23 00:00:00	2013-05-14 00:00:00	2013-06-29 23:59:00
92	24	2013-07-01 00:00:00	2013-05-14 00:00:00	2013-07-07 23:59:00
92	25	2013-07-09 00:00:00	2013-05-14 00:00:00	2013-07-15 23:59:00
91	18	2013-05-14 00:00:00	2013-05-14 00:00:00	2013-05-20 23:59:00
94	18	2013-05-14 00:00:00	2013-05-14 00:00:00	2013-05-20 23:59:00
94	19	2013-05-22 00:00:00	2013-05-14 00:00:00	2013-05-28 23:59:00
94	20	2013-05-30 00:00:00	2013-05-14 00:00:00	2013-06-05 23:59:00
94	21	2013-06-07 00:00:00	2013-05-14 00:00:00	2013-06-13 23:59:00
94	22	2013-06-15 00:00:00	2013-05-14 00:00:00	2013-06-21 23:59:00
94	23	2013-06-23 00:00:00	2013-05-14 00:00:00	2013-06-29 23:59:00
94	24	2013-07-01 00:00:00	2013-05-14 00:00:00	2013-07-07 23:59:00
94	25	2013-07-09 00:00:00	2013-05-14 00:00:00	2013-07-15 23:59:00
95	18	2013-05-14 00:00:00	2013-05-14 00:00:00	2013-05-20 23:59:00
95	19	2013-05-22 00:00:00	2013-05-14 00:00:00	2013-05-28 23:59:00
95	20	2013-05-30 00:00:00	2013-05-14 00:00:00	2013-06-05 23:59:00
95	21	2013-06-07 00:00:00	2013-05-14 00:00:00	2013-06-13 23:59:00
95	22	2013-06-15 00:00:00	2013-05-14 00:00:00	2013-06-21 23:59:00
95	23	2013-06-23 00:00:00	2013-05-14 00:00:00	2013-06-29 23:59:00
95	24	2013-07-01 00:00:00	2013-05-14 00:00:00	2013-07-07 23:59:00
95	25	2013-07-09 00:00:00	2013-05-14 00:00:00	2013-07-15 23:59:00
93	19	2013-05-22 00:00:00	\N	2013-05-28 23:59:00
93	20	2013-05-30 00:00:00	\N	2013-06-05 23:59:00
93	21	2013-06-07 00:00:00	\N	2013-06-13 23:59:00
93	22	2013-06-15 00:00:00	\N	2013-06-21 23:59:00
93	23	2013-06-23 00:00:00	\N	2013-06-29 23:59:00
93	24	2013-07-01 00:00:00	\N	2013-07-07 23:59:00
93	25	2013-07-09 00:00:00	\N	2013-07-15 23:59:00
93	18	2013-05-14 00:00:00	2013-05-07 00:00:00	2013-05-20 23:59:00
\.


--
-- TOC entry 2831 (class 0 OID 16785)
-- Dependencies: 259
-- Data for Name: many_encargado_has_many_area_vision; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY many_encargado_has_many_area_vision (id_encargado, id_area_vision) FROM stdin;
\.


--
-- TOC entry 2832 (class 0 OID 16788)
-- Dependencies: 260
-- Data for Name: many_herramienta_has_many_consolidacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY many_herramienta_has_many_consolidacion (id_herramienta, id_consolida, fecha_hora_aplicacion, fecha_hora_propuesta, hecho) FROM stdin;
4	91	\N	2013-05-16 16:32:53	f
5	91	\N	2013-05-21 16:32:53	f
6	91	\N	2013-05-28 16:32:53	f
7	91	\N	2013-05-28 16:32:53	f
10	91	\N	2013-07-03 16:32:53	f
4	92	\N	2013-05-16 17:12:03	f
5	92	\N	2013-05-21 17:12:03	f
6	92	\N	2013-05-28 17:12:03	f
7	92	\N	2013-05-28 17:12:03	f
10	92	\N	2013-07-03 17:12:03	f
5	93	\N	2013-05-21 17:41:37	f
6	93	\N	2013-05-28 17:41:37	f
7	93	\N	2013-05-28 17:41:37	f
10	93	\N	2013-07-03 17:41:37	f
4	94	\N	2013-05-16 17:42:52	f
5	94	\N	2013-05-21 17:42:52	f
6	94	\N	2013-05-28 17:42:52	f
7	94	\N	2013-05-28 17:42:52	f
10	94	\N	2013-07-03 17:42:52	f
4	95	\N	2013-05-16 18:29:37	f
5	95	\N	2013-05-21 18:29:37	f
6	95	\N	2013-05-28 18:29:37	f
7	95	\N	2013-05-28 18:29:37	f
10	95	\N	2013-07-03 18:29:37	f
4	93	2013-02-05 00:00:00	2013-05-16 17:41:37	t
\.


--
-- TOC entry 2833 (class 0 OID 16792)
-- Dependencies: 261
-- Data for Name: many_usuario_has_many_rol; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY many_usuario_has_many_rol (id_usuario, id_rol) FROM stdin;
1	2
27	1
\.


--
-- TOC entry 2834 (class 0 OID 16795)
-- Dependencies: 262
-- Data for Name: matric; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY matric (id, fecha, activo, id_persona_estudiante, id_curso_impartido) FROM stdin;
\.


--
-- TOC entry 3023 (class 0 OID 0)
-- Dependencies: 263
-- Name: matric_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('matric_id_seq', 29, true);


--
-- TOC entry 2787 (class 0 OID 16496)
-- Dependencies: 192
-- Data for Name: miembro; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY miembro (apto_consolidar, fecha_obtencion, activo, id, id_celula, id_red) FROM stdin;
f	1997-01-01	t	65	\N	\N
f	2012-03-09	t	66	\N	\N
f	2000-03-01	t	67	\N	\N
f	2002-08-03	t	68	\N	\N
f	2005-06-01	t	69	\N	\N
f	2000-01-01	t	71	\N	\N
f	2013-01-23	t	72	\N	\N
f	2013-05-01	t	73	\N	\N
f	2011-05-01	t	74	\N	\N
f	1983-03-25	t	75	\N	\N
f	2010-03-01	t	76	\N	\N
f	2013-01-22	t	77	\N	\N
f	2013-05-01	t	78	\N	\N
f	2006-05-01	t	80	\N	\N
f	2003-12-01	t	82	\N	\N
f	1986-01-01	t	84	\N	\N
f	2013-04-30	t	85	\N	\N
f	2013-05-09	t	86	\N	\N
f	2012-11-12	t	88	\N	\N
f	2013-04-23	t	89	\N	\N
f	1989-11-18	t	90	\N	\N
f	1994-01-02	t	91	\N	\N
f	2013-04-30	t	95	\N	H4
f	2013-04-30	t	96	15	MJ3
f	2003-02-23	t	79	15	MJ3
t	2013-05-01	t	81	16	HJ3
f	2013-05-15	t	97	16	HJ3
f	2013-01-26	t	83	17	H4
f	2013-05-01	t	98	17	H4
f	1991-01-01	t	87	\N	H3
\.


--
-- TOC entry 2820 (class 0 OID 16694)
-- Dependencies: 238
-- Data for Name: misionero; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY misionero (fecha_obtencion, activo, fecha_fin, id) FROM stdin;
\.


--
-- TOC entry 2836 (class 0 OID 16801)
-- Dependencies: 264
-- Data for Name: nuevo_convertido; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY nuevo_convertido (fecha_conversion, peticion, consolidado, id, id_celula, id_red, id_lugar) FROM stdin;
1997-01-01	 Toda su familia se convierta al Señor.	f	65	\N	\N	1
2012-03-09	 Por familia y etudio	f	66	\N	\N	1
2000-03-01	 Por su familia	f	67	\N	\N	1
2002-08-03	 Conversión de su familia	f	68	\N	\N	1
2005-06-01	 Que Dios guarde y haga crecer espiritualmente a mi familia.	f	69	\N	\N	1
2000-01-01	 	f	71	\N	\N	1
2013-01-23	 Creciemto Espiritual	f	72	\N	\N	1
2013-05-01	Estabilidad laboral	f	73	\N	\N	1
2011-05-01	 Crecimiento Espiritual	f	74	\N	\N	1
1983-03-25	 Orar por su familia, en especial por Humberto.	f	75	\N	\N	1
2010-03-01	 Por la conversión de su esposo.	f	76	\N	\N	1
2013-01-22	 Por su familia	f	77	\N	\N	1
2013-05-01	 Por la salvacion de mi alma	f	78	\N	\N	1
2006-05-01	 	f	80	\N	\N	1
2003-12-01	Comprencion de Familia 	f	82	\N	\N	1
1986-01-01	 	f	84	\N	\N	1
2013-04-30	 orar por él	f	85	\N	\N	1
2013-05-09	 	f	86	\N	\N	1
2012-11-12	 orar por salud	f	88	\N	\N	1
2013-04-23	 	f	89	\N	\N	1
1989-11-18	 	f	90	\N	\N	1
1994-01-02	 	f	91	\N	\N	1
2013-04-30	 orar por familia	f	95	\N	H4	1
2003-02-23	   	f	79	15	MJ3	1
2013-04-30	 	t	96	15	MJ3	1
2013-05-01	    	t	81	16	HJ3	1
2013-05-01	 	f	98	17	H4	1
2013-05-15	 orar por salud 	t	97	16	HJ3	1
2013-01-26	      	t	83	17	H4	1
1991-01-01	  	t	87	\N	H3	1
\.


--
-- TOC entry 2821 (class 0 OID 16713)
-- Dependencies: 242
-- Data for Name: pastor_asociado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY pastor_asociado (fecha_obtencion, activo, fecha_fin, id) FROM stdin;
\.


--
-- TOC entry 2822 (class 0 OID 16722)
-- Dependencies: 244
-- Data for Name: pastor_ejecutivo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY pastor_ejecutivo (fecha_obtencion, activo, fecha_fin, id) FROM stdin;
\.


--
-- TOC entry 2788 (class 0 OID 16501)
-- Dependencies: 193
-- Data for Name: persona; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY persona (id, nombre, apellidos, estado_civil, edad, telefono, celular, fecha_nacimiento, email, website, sexo, id_ubicacion, id_iglesia) FROM stdin;
65	Teresa	Vásquez Huamán	0	44		951423181	1968-05-15	\N	\N	2	135	\N
66	Jean Carlos	Otiniano Sandoval	0	15		943478729	1997-08-19	jeancas.97@hotmail.com	\N	1	136	\N
67	Analí	Rodríguez Escobar	0	25	044381042		1987-07-21	chocoany21@hotmail.com	\N	2	137	\N
68	Sandra Banesa	Gil Aguilera	0	33	044242727		1979-05-08	sandragilaguilera_@hotmail.com	\N	2	138	\N
69	Alan Raul	Mariñas Rengifo	1	29			1984-10-04	alanraul_mr@hotmail.com	\N	1	139	\N
71	Sara Vanessa	Montoya Távara	0	16		941748685	1997-06-30	amy_31_7@hotmail.com	\N	2	141	\N
72	Nataly	Vega Sandoval	0	19		959178534	1994-03-04	nataw.94@hotmail.com	\N	2	142	\N
73	Ebelyn Jacqueline	Rodríguez Vásquez	0	24		978176220	2013-01-01	ebelynrodriguez200788@hotmail.com	\N	2	143	\N
74	Samy Stivie	Aponte Diaz	0	20		980484005	1992-06-15	smapontedz@hotmail.com	\N	1	144	\N
75	Yolving	Florián León	1	48	044408513	945053390	1964-08-05	yolving43@hotmail.com	\N	1	145	\N
76	María Julia	Sánchez Morales de Pardo	1	47	044207138	949915834	1966-04-03	maja_sm66@hotmail.com	\N	2	146	\N
77	Luis Alberto	Alvarez Briceño	0	18			1994-12-27	capricornio_eleroe_3@hotmail.com	\N	1	147	\N
78	Edson Eduardo 	De la Cruz  Coronado	0	17	044280803	992255809	1997-02-09	gaali_15_14@hotmail.com	\N	1	148	\N
80	Geancarlo Piero	Diaz Azabache	0	31	044292241	959598703	1982-08-26	geanpierodiaz@hotmail.com	\N	1	150	\N
82	Gina Paola	Miranda C	1	27	044213274	948744681	1988-06-05	gina_vc12@hotmail.com	\N	2	152	\N
84	Ivonne del Milagro	Boulangger Morquencho	0	43	044398028	949942654	1969-10-10	ibm_1969@hitmail.com	\N	2	154	\N
85	Percy Cooper	Garcia Castillo	0	26		981342442	1986-09-05	\N	\N	1	155	\N
86	Carmita Liset	Leon Pretel	0	30		981656507	1984-08-09	perla_zuta@hotmail.com	\N	2	156	\N
88	Saúl Jesús	Torres Castillo	0	25	044414019		1987-12-10	\N	\N	1	158	\N
89	Elizabeth Pamela	Plascencia Sangay	0	25			1987-07-06	pami_29@yahoo.es	\N	1	159	\N
90	Asminy Erotida	Tavara Guerra	1	46		973305167	1966-07-31	negrita19661@hotmail.com	\N	2	160	\N
91	Ivana Madeleine	Silva Espinoza	0	40		949630324	1972-12-08	ivamad@hotmail.com	\N	1	161	\N
95	Juan	Pérez	0	23			2013-05-08	\N	\N	1	196	\N
96	Pierina	Gutierrez	0	21			2013-05-01	\N	\N	2	202	\N
79	Cynthia Mercedes	Quiroz Gutierrez	0	22	044381043	948363124	1990-04-26	cymer_26@hotmail.com		1	149	\N
81	Marks Arturo	Calderón Niquín	0	24	044239627	968138990	1989-04-26	artmar89@gmail.com		1	151	\N
97	Hector	Luna	0	23			2013-05-07			1	203	\N
83	Saulo Ernesto	Montoya Peña	0	41		949587916	1972-05-10			1	153	\N
98	Martin	Calderón Niquín	0	18			2013-05-08	\N	\N	1	206	\N
87	Jose Adolfo	Montoya Peña	0	45		969690017	1967-12-08			1	157	\N
\.


--
-- TOC entry 3024 (class 0 OID 0)
-- Dependencies: 266
-- Name: persona_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('persona_id_seq', 98, true);


--
-- TOC entry 2838 (class 0 OID 16814)
-- Dependencies: 267
-- Data for Name: prerequisito; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY prerequisito (id_curso, id_curso1, id_curso2) FROM stdin;
\.


--
-- TOC entry 3025 (class 0 OID 0)
-- Dependencies: 268
-- Name: prerequisito_id_curso_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('prerequisito_id_curso_seq', 2, true);


--
-- TOC entry 2819 (class 0 OID 16684)
-- Dependencies: 236
-- Data for Name: red; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY red (id, tipo, id_ubicacion, id_iglesia, id_lider_red, id_pastor_asociado, inicio, activo) FROM stdin;
HJ1	3	172	11	\N	\N	2013-05-13	t
MJ1	4	173	11	\N	\N	2013-05-13	t
M1	2	174	11	\N	\N	2013-05-13	t
H2	1	175	11	\N	\N	2013-05-13	t
M2	2	176	11	\N	\N	2013-05-13	t
MJ2	0	177	11	\N	\N	2013-05-13	t
HJ2	3	178	11	\N	\N	2013-05-13	t
M3	2	179	11	\N	\N	2013-05-13	t
M4	0	184	11	\N	\N	2013-05-13	t
MJ4	4	185	11	\N	\N	2013-05-13	t
HJ4	0	186	11	\N	\N	2013-05-13	t
M5	2	187	11	\N	\N	2013-05-13	t
H5	1	188	11	\N	\N	2013-05-13	t
MJ5	4	189	11	\N	\N	2013-05-13	t
HJ5	0	190	11	\N	\N	2013-05-13	t
H6	1	191	11	\N	\N	2013-05-13	t
M6	2	192	11	\N	\N	2013-05-13	t
HJ6	3	193	11	\N	\N	2013-05-13	t
MJ6	4	194	11	\N	\N	2013-05-13	t
H4	1	183	11	83	\N	2013-05-13	t
HJ3	3	182	11	81	\N	2013-05-13	t
MJ3	4	181	11	79	\N	2013-05-13	t
H3	1	195	11	87	\N	2013-05-13	t
\.


--
-- TOC entry 2840 (class 0 OID 16819)
-- Dependencies: 269
-- Data for Name: red_social; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY red_social (id, enlace, tipo, id_persona) FROM stdin;
\.


--
-- TOC entry 3026 (class 0 OID 0)
-- Dependencies: 270
-- Name: red_social_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('red_social_id_seq', 1, true);


--
-- TOC entry 2842 (class 0 OID 16824)
-- Dependencies: 271
-- Data for Name: rol; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY rol (id, nombre) FROM stdin;
1	ROLE_USER
2	ROLE_ADMIN
3	ROLE_LIDER
4	ROLE_LIDER_RED
5	ROLE_MISIONERO
6	ROLE_PASTOR_EJECUTIVO
7	ROLE_PASTOR_ASOCIADO
8	ROLE_GANAR
9	ROLE_CONSOLIDAR
10	ROLE_DISCIPULAR
11	ROLE_ESTUDIANTE
12	ROLE_PROFESOR
13	ROLE_ENVIAR
\.


--
-- TOC entry 3027 (class 0 OID 0)
-- Dependencies: 272
-- Name: rol_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('rol_id_seq', 2, true);


--
-- TOC entry 2824 (class 0 OID 16758)
-- Dependencies: 251
-- Data for Name: tema_celula; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tema_celula (id, titutlo, autor, descripcion, fecha, tipo) FROM stdin;
11	Primer Amor	Jhonatan Boulangger	trata sobre el primer amor encontrado con Dios 	2013-05-14	Evangelistica
\.


--
-- TOC entry 3028 (class 0 OID 0)
-- Dependencies: 273
-- Name: tema_celula_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tema_celula_id_seq', 11, true);


--
-- TOC entry 2845 (class 0 OID 16831)
-- Dependencies: 274
-- Data for Name: tema_curso; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tema_curso (id, activo, fecha_creacion, descripcion, tipo, id_curso) FROM stdin;
\.


--
-- TOC entry 3029 (class 0 OID 0)
-- Dependencies: 275
-- Name: tema_curso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tema_curso_id_seq', 7, true);


--
-- TOC entry 2847 (class 0 OID 16840)
-- Dependencies: 276
-- Data for Name: tema_leche; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tema_leche (id, titulo, id_leche_espiritual) FROM stdin;
18	Arrepentimiento	23
19	Nuevo Nacimineto	23
20	La comunicación permanente	23
21	El alimento que necesitas	23
22	Comunión entre hermanos	23
23	La importancia de tener un encuentro	23
24	Identificando a nuestro enemigo	23
25	Preparandonos para la liberacion	23
\.


--
-- TOC entry 3030 (class 0 OID 0)
-- Dependencies: 277
-- Name: tema_leche_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tema_leche_id_seq', 25, true);


--
-- TOC entry 2818 (class 0 OID 16636)
-- Dependencies: 229
-- Data for Name: ubicacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ubicacion (id, direccion, latitud, longitud, referencia, id_ubigeo) FROM stdin;
134	clm trujillo	-8.09927609999999909	-79.037037899999973	clm house	1128
135	Las Dunas Mz. 76 Lt. 10	-8.18638722672885599	-78.9919254308776999	Alto Moche	1134
136	Jose Galves 1088	-8.09934039999999911	-79.0370447999999897	Jose Galves 1088	1128
137	Las Dunas Mz. 73 Lt. 20	-8.17307013844183849	-79.0002724653320456	Alto Moche	1134
138	Jose Gil de Castro Castro Mz E Lote 40 El Bosque	-8.09931599999999996	-79.0370442999999909	El Bosque	1128
139	31 enero 855 Florencia de Mora	-8.07984090508399255	-79.0222875559753675	31 enero 855 Florencia de Mora	1130
140	31 enero 855 Florencia de Mora	-8.07984090508399255	-79.0222875559753675	31 enero 855 Florencia de Mora	1130
141	Las Dunas - Miramar Mz. 73 Lt. 17	-8.09931899999999949	-79.0370080000000144	Alto Moche	1134
142	Jose Galvez 1088 Chicago	-8.0993031000000002	-79.0370525000000157	Chicago	1128
143	Las Dunas Mz. 76 Lt.10	-8.09931899999999949	-79.0370080000000144	Alto Moche	1134
144	Urb. Alameda Mz. G Lt 11	-8.09930239999999912	-79.0370467999999846	La Alameda	1128
145	Av. Jesús Maestro Mz. 72 Lt. 26	-8.09931899999999949	-79.0370080000000144	Alto Moche	1134
146	Av. Nicolás de Piérola 1360-1372	-8.09931899999999949	-79.0370080000000144	Urb. San Fernando	1133
147	Jose Felix Aldao 731- 737	-8.09930780000000006	-79.037042299999996	La Esperanza	1132
148	Las Casuarinas 583 Urbanizacion Santa Edelmira	-8.13555545133079505	-79.0428560481933573		1138
150	Av España 133 Urb. San Andres	-8.09926579999999952	-79.0369992999999909	Av. España	1128
152	Jr.31de Enero 855 Florencia de Mora	-8.07826879677623921	-79.0224806750244397	Jr.31 de Enero 855 Florencia de Mora	1130
154	Julia Codesido 737 Urb. Santo Dominguito	-8.09931759999999912	-79.0370543999999882	Santo Dominguito	1128
155	Monserrate V etapa Mz A2 Bloq a departamento 103	-8.12413709186989053	-79.0337437184936675	Monserrate V etapa Mz A2 Bloq a departamento 103	1128
156	Baca Flor #181 Santo dominguito	-8.11193804743342639	-79.0119702770629715	Baca Flor #181 Santo Dominguito	1128
158	Mz 7 Lote 27 Fraternidad	-8.06920068213587705	-79.0461462529785308	Mz 7 Lote 27 Fraternidad	1132
159	Carlos María de Alvera 1664	-8.06296455129380973	-79.0502769260253899	La Esperanza	1132
160	Las Dunas Mz. 73 Lt, 7	-8.09926659999999998	-79.0369997999999896	Miramar	1134
161	Mz 4 Lote 11 Urb. Sol de las Delicias	-8.18036374120989507	-79.012352861718739	Mz 4 Lote 11 Urb. Sol de las Delicias	1134
166	clm truj	-8.09794400000000003	-79.0370447999999897	clm truj	1128
170	clm house	-8.09907059999999923	-79.0368369000000257	clm house	1128
171	CLM HOUSE	-8.09912429999999972	-79.036877899999979	CLM HOUSE	1136
172	CLM Trujillo	-8.09899850000000043	-79.0366576000000123	CLM Trujillo	1128
173	CLM Trujillo	-8.09898079999999965	-79.0366624000000115	CLM Trujillo	1128
174	CLM Trujillo	-8.09898669999999932	-79.0366632000000209	CLM Trujillo	1128
175	CLM Trujillo	-8.09899740000000001	-79.0367054999999823	CLM Trujillo	1128
176	CLM Trujillo	-8.09900309999999912	-79.0367133999999965	CLM Trujillo	1128
177	CLM Trujillo	-8.09898649999999876	-79.036707100000001	CLM Trujillo	1128
178	CLM Trujillo	-8.09907639999999951	-79.0367911999999819	CLM Trujillo	1128
179	CLM Trujillo	-8.09900309999999912	-79.0367133999999965	CLM Trujillo	1128
180	CLM Trujillo	-8.0989899999999988	-79.036700900000028	CLM Trujillo	1128
184	CLM Trujillo	-8.09897169999999988	-79.0366606999999703	CLM Trujillo	1128
185	CLM Trujillo	-8.09897340000000021	-79.0366640999999959	CLM Trujillo	1128
186	CLM Trujillo	-8.09897119999999937	-79.0366748000000143	CLM Trujillo	1128
187	CLM Trujillo	-8.09897460000000002	-79.0366635000000315	CLM Trujillo	1128
188	CLM Trujillo	-8.09897310000000026	-79.0366644000000065	CLM Trujillo	1128
189	CLM Trujillo	-8.09897489999999998	-79.0366632000000209	CLM Trujillo	1128
190	CLM Trujillo	-8.09897819999999946	-79.0366796999999792	CLM Trujillo	1128
191	CLM Trujillo	-8.09897340000000021	-79.0366640999999959	CLM Trujillo	1128
192	CLM Trujillo	-8.09897340000000021	-79.0366640999999959	CLM Trujillo	1128
193	CLM Trujillo	-8.09897990000000156	-79.0366650999999933	CLM Trujillo	1128
194	CLM Trujillo	-8.09899289999999894	-79.0366500999999744	CLM Trujillo	1128
183	CLM House	-8.09899000000000058	-79.036700900000028	cerca al dorado	1128
196	clm house	-8.09794400000000003	-79.0370447999999897	clm house	1128
205	clm house	-8.09794400000000003	-79.0370447999999897	clm house	1128
153	Julia Codesido737 Urb. Santo Dominguito	-8.09931710000000038	-79.0370513000000301	Santo Dominguito	1128
181	CLM Trujillo	-8.09898349999999922	-79.0367097999999828	CLM Trujillo	1128
206	CLM House	-8.09794400000000003	-79.0370447999999897	CLM House	1128
195	CLM House	-8.18718666046717125	-78.9918068569335787	CLM House	1128
157	Las Dunas Mz. 73 Lt. 17	-8.09928580000000053	-79.0370270999999889	Moche	1134
182	CLM Trujillo	-8.09899219999999964	-79.0367013000000043	CLM Trujillo	1128
201	UNT	-8.11306923835696914	-79.0364439851807106	UNT	1128
202	clm house	-8.09794400000000003	-79.0370447999999897	clm house	1128
149	Las Dunas - Miramar Mz. 76 Lt. 8 	-8.09931899999999949	-79.0370080000000144	Alto Moche	1134
204	UNT	-8.11302675252903072	-79.0365298158691303	UNT	1128
151	Av. Juan Velasco Mz. 4 Lt. 16	-8.09931899999999949	-79.0370080000000144	Sector II El Milagro	1131
203	El parque industrial	-8.09794400000000003	-79.0370447999999897	El parque industrial	1132
\.


--
-- TOC entry 3031 (class 0 OID 0)
-- Dependencies: 278
-- Name: ubicacion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ubicacion_id_seq', 206, true);


--
-- TOC entry 2823 (class 0 OID 16751)
-- Dependencies: 249
-- Data for Name: ubigeo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY ubigeo (id, coddepartamento, codprovincia, coddistrito, departamento, provincia, distrito, lat, long) FROM stdin;
1471	17	1	1	MADRE DE DIOS	TAMBOPATA	TAMBOPATA	-12.2199395000000006	-69.859740599999995
1472	17	1	2	MADRE DE DIOS	TAMBOPATA	INAMBARI	-12.2199395000000006	-69.859740599999995
1473	17	1	3	MADRE DE DIOS	TAMBOPATA	LAS PIEDRAS	-12.2199395000000006	-69.859740599999995
1474	17	1	4	MADRE DE DIOS	TAMBOPATA	LABERINTO	-12.2199395000000006	-69.859740599999995
1475	17	2	1	MADRE DE DIOS	MANU	MANU	-12.2199395000000006	-69.859740599999995
1476	17	2	2	MADRE DE DIOS	MANU	FITZCARRALD	-12.2199395000000006	-69.859740599999995
1477	17	2	3	MADRE DE DIOS	MANU	MADRE DE DIOS	-12.2199395000000006	-69.859740599999995
1478	17	2	4	MADRE DE DIOS	MANU	HUEPETUHE	-12.2199395000000006	-69.859740599999995
1479	17	3	1	MADRE DE DIOS	TAHUAMANU	IÑAPARI	-12.2199395000000006	-69.859740599999995
1480	17	3	2	MADRE DE DIOS	TAHUAMANU	IBERIA	-12.2199395000000006	-69.859740599999995
1481	17	3	3	MADRE DE DIOS	TAHUAMANU	TAHUAMANU	-12.2199395000000006	-69.859740599999995
1780	23	1	1	TACNA	TACNA	TACNA	-15.8431093000000001	-70.0232632000000024
1781	23	1	2	TACNA	TACNA	ALTO DE LA ALIANZA	-15.8431093000000001	-70.0232632000000024
1782	23	1	3	TACNA	TACNA	CALANA	-15.8431093000000001	-70.0232632000000024
1783	23	1	4	TACNA	TACNA	CIUDAD NUEVA	-15.8431093000000001	-70.0232632000000024
1784	23	1	5	TACNA	TACNA	INCLAN	-15.8431093000000001	-70.0232632000000024
1785	23	1	6	TACNA	TACNA	PACHIA	-15.8431093000000001	-70.0232632000000024
1786	23	1	7	TACNA	TACNA	PALCA	-15.8431093000000001	-70.0232632000000024
1787	23	1	8	TACNA	TACNA	POCOLLAY	-15.8431093000000001	-70.0232632000000024
1788	23	1	9	TACNA	TACNA	SAMA	-15.8431093000000001	-70.0232632000000024
1789	23	1	10	TACNA	TACNA	CORONEL GREGORIO ALBARRACIN LANCHIPA	-15.8431093000000001	-70.0232632000000024
1790	23	2	1	TACNA	CANDARAVE	CANDARAVE	-15.8431093000000001	-70.0232632000000024
1791	23	2	2	TACNA	CANDARAVE	CAIRANI	-15.8431093000000001	-70.0232632000000024
1792	23	2	3	TACNA	CANDARAVE	CAMILACA	-15.8431093000000001	-70.0232632000000024
1793	23	2	4	TACNA	CANDARAVE	CURIBAYA	-15.8431093000000001	-70.0232632000000024
1794	23	2	5	TACNA	CANDARAVE	HUANUARA	-15.8431093000000001	-70.0232632000000024
1795	23	2	6	TACNA	CANDARAVE	QUILAHUANI	-15.8431093000000001	-70.0232632000000024
1796	23	3	1	TACNA	JORGE BASADRE	LOCUMBA	-15.8431093000000001	-70.0232632000000024
1797	23	3	2	TACNA	JORGE BASADRE	ILABAYA	-15.8431093000000001	-70.0232632000000024
1798	23	3	3	TACNA	JORGE BASADRE	ITE	-15.8431093000000001	-70.0232632000000024
1799	23	4	1	TACNA	TARATA	TARATA	-15.8431093000000001	-70.0232632000000024
1800	23	4	2	TACNA	TARATA	HEROES ALBARRACIN	-15.8431093000000001	-70.0232632000000024
1801	23	4	3	TACNA	TARATA	ESTIQUE	-15.8431093000000001	-70.0232632000000024
1802	23	4	4	TACNA	TARATA	ESTIQUE-PAMPA	-15.8431093000000001	-70.0232632000000024
1803	23	4	5	TACNA	TARATA	SITAJARA	-15.8431093000000001	-70.0232632000000024
1804	23	4	6	TACNA	TARATA	SUSAPAYA	-15.8431093000000001	-70.0232632000000024
1805	23	4	7	TACNA	TARATA	TARUCACHI	-15.8431093000000001	-70.0232632000000024
1806	23	4	8	TACNA	TARATA	TICACO	-15.8431093000000001	-70.0232632000000024
962	11	1	1	ICA	ICA	ICA	-14.0655564999999996	-75.7440863000000064
963	11	1	2	ICA	ICA	LA TINGUIÑA	-14.0655564999999996	-75.7440863000000064
964	11	1	3	ICA	ICA	LOS AQUIJES	-14.0655564999999996	-75.7440863000000064
965	11	1	4	ICA	ICA	OCUCAJE	-14.0655564999999996	-75.7440863000000064
966	11	1	5	ICA	ICA	PACHACUTEC	-14.0655564999999996	-75.7440863000000064
967	11	1	6	ICA	ICA	PARCONA	-14.0655564999999996	-75.7440863000000064
968	11	1	7	ICA	ICA	PUEBLO NUEVO	-14.0655564999999996	-75.7440863000000064
969	11	1	8	ICA	ICA	SALAS	-14.0655564999999996	-75.7440863000000064
970	11	1	9	ICA	ICA	SAN JOSE DE LOS MOLINOS	-14.0655564999999996	-75.7440863000000064
971	11	1	10	ICA	ICA	SAN JUAN BAUTISTA	-14.0655564999999996	-75.7440863000000064
972	11	1	11	ICA	ICA	SANTIAGO	-14.0655564999999996	-75.7440863000000064
973	11	1	12	ICA	ICA	SUBTANJALLA	-14.0655564999999996	-75.7440863000000064
974	11	1	13	ICA	ICA	TATE	-14.0655564999999996	-75.7440863000000064
975	11	1	14	ICA	ICA	YAUCA DEL ROSARIO	-14.0655564999999996	-75.7440863000000064
976	11	2	1	ICA	CHINCHA	CHINCHA ALTA	-14.0655564999999996	-75.7440863000000064
977	11	2	2	ICA	CHINCHA	ALTO LARAN	-14.0655564999999996	-75.7440863000000064
978	11	2	3	ICA	CHINCHA	CHAVIN	-14.0655564999999996	-75.7440863000000064
979	11	2	4	ICA	CHINCHA	CHINCHA BAJA	-14.0655564999999996	-75.7440863000000064
980	11	2	5	ICA	CHINCHA	EL CARMEN	-14.0655564999999996	-75.7440863000000064
981	11	2	6	ICA	CHINCHA	GROCIO PRADO	-14.0655564999999996	-75.7440863000000064
982	11	2	7	ICA	CHINCHA	PUEBLO NUEVO	-14.0655564999999996	-75.7440863000000064
983	11	2	8	ICA	CHINCHA	SAN JUAN DE YANAC	-14.0655564999999996	-75.7440863000000064
984	11	2	9	ICA	CHINCHA	SAN PEDRO DE HUACARPANA	-14.0655564999999996	-75.7440863000000064
985	11	2	10	ICA	CHINCHA	SUNAMPE	-14.0655564999999996	-75.7440863000000064
986	11	2	11	ICA	CHINCHA	TAMBO DE MORA	-14.0655564999999996	-75.7440863000000064
987	11	3	1	ICA	NAZCA	NAZCA	-14.0655564999999996	-75.7440863000000064
988	11	3	2	ICA	NAZCA	CHANGUILLO	-14.0655564999999996	-75.7440863000000064
989	11	3	3	ICA	NAZCA	EL INGENIO	-14.0655564999999996	-75.7440863000000064
990	11	3	4	ICA	NAZCA	MARCONA	-14.0655564999999996	-75.7440863000000064
991	11	3	5	ICA	NAZCA	VISTA ALEGRE	-14.0655564999999996	-75.7440863000000064
992	11	4	1	ICA	PALPA	PALPA	-14.0655564999999996	-75.7440863000000064
993	11	4	2	ICA	PALPA	LLIPATA	-14.0655564999999996	-75.7440863000000064
994	11	4	3	ICA	PALPA	RIO GRANDE	-14.0655564999999996	-75.7440863000000064
995	11	4	4	ICA	PALPA	SANTA CRUZ	-14.0655564999999996	-75.7440863000000064
996	11	4	5	ICA	PALPA	TIBILLO	-14.0655564999999996	-75.7440863000000064
997	11	5	1	ICA	PISCO	PISCO	-14.0655564999999996	-75.7440863000000064
998	11	5	2	ICA	PISCO	HUANCANO	-14.0655564999999996	-75.7440863000000064
999	11	5	3	ICA	PISCO	HUMAY	-14.0655564999999996	-75.7440863000000064
1000	11	5	4	ICA	PISCO	INDEPENDENCIA	-14.0655564999999996	-75.7440863000000064
1001	11	5	5	ICA	PISCO	PARACAS	-14.0655564999999996	-75.7440863000000064
1002	11	5	6	ICA	PISCO	SAN ANDRES	-14.0655564999999996	-75.7440863000000064
1003	11	5	7	ICA	PISCO	SAN CLEMENTE	-14.0655564999999996	-75.7440863000000064
1004	11	5	8	ICA	PISCO	TUPAC AMARU INCA	-14.0655564999999996	-75.7440863000000064
1236	14	2	6	LAMBAYEQUE	FERREÑAFE	PUEBLO NUEVO	-6.50080159999999996	-79.9180869000000058
1237	14	3	1	LAMBAYEQUE	LAMBAYEQUE	LAMBAYEQUE	-6.50080159999999996	-79.9180869000000058
1238	14	3	2	LAMBAYEQUE	LAMBAYEQUE	CHOCHOPE	-6.50080159999999996	-79.9180869000000058
1239	14	3	3	LAMBAYEQUE	LAMBAYEQUE	ILLIMO	-6.50080159999999996	-79.9180869000000058
1240	14	3	4	LAMBAYEQUE	LAMBAYEQUE	JAYANCA	-6.50080159999999996	-79.9180869000000058
1241	14	3	5	LAMBAYEQUE	LAMBAYEQUE	MOCHUMI	-6.50080159999999996	-79.9180869000000058
1242	14	3	6	LAMBAYEQUE	LAMBAYEQUE	MORROPE	-6.50080159999999996	-79.9180869000000058
1243	14	3	7	LAMBAYEQUE	LAMBAYEQUE	MOTUPE	-6.50080159999999996	-79.9180869000000058
1244	14	3	8	LAMBAYEQUE	LAMBAYEQUE	OLMOS	-6.50080159999999996	-79.9180869000000058
1245	14	3	9	LAMBAYEQUE	LAMBAYEQUE	PACORA	-6.50080159999999996	-79.9180869000000058
1246	14	3	10	LAMBAYEQUE	LAMBAYEQUE	SALAS	-6.50080159999999996	-79.9180869000000058
1247	14	3	11	LAMBAYEQUE	LAMBAYEQUE	SAN JOSE	-6.50080159999999996	-79.9180869000000058
1248	14	3	12	LAMBAYEQUE	LAMBAYEQUE	TUCUME	-6.50080159999999996	-79.9180869000000058
1044	12	2	12	JUNIN	CONCEPCION	NUEVE DE JULIO	-11.3274352	-75.3006749999999982
1062	12	4	9	JUNIN	JAUJA	HUARIPAMPA	-11.3274352	-75.3006749999999982
1063	12	4	10	JUNIN	JAUJA	HUERTAS	-11.3274352	-75.3006749999999982
1064	12	4	11	JUNIN	JAUJA	JANJAILLO	-11.3274352	-75.3006749999999982
1065	12	4	12	JUNIN	JAUJA	JULCAN	-11.3274352	-75.3006749999999982
1066	12	4	13	JUNIN	JAUJA	LEONOR ORDOÑEZ	-11.3274352	-75.3006749999999982
1067	12	4	14	JUNIN	JAUJA	LLOCLLAPAMPA	-11.3274352	-75.3006749999999982
1068	12	4	15	JUNIN	JAUJA	MARCO	-11.3274352	-75.3006749999999982
1069	12	4	16	JUNIN	JAUJA	MASMA	-11.3274352	-75.3006749999999982
1070	12	4	17	JUNIN	JAUJA	MASMA CHICCHE	-11.3274352	-75.3006749999999982
1071	12	4	18	JUNIN	JAUJA	MOLINOS	-11.3274352	-75.3006749999999982
1072	12	4	19	JUNIN	JAUJA	MONOBAMBA	-11.3274352	-75.3006749999999982
1073	12	4	20	JUNIN	JAUJA	MUQUI	-11.3274352	-75.3006749999999982
1074	12	4	21	JUNIN	JAUJA	MUQUIYAUYO	-11.3274352	-75.3006749999999982
1075	12	4	22	JUNIN	JAUJA	PACA	-11.3274352	-75.3006749999999982
1076	12	4	23	JUNIN	JAUJA	PACCHA	-11.3274352	-75.3006749999999982
1077	12	4	24	JUNIN	JAUJA	PANCAN	-11.3274352	-75.3006749999999982
1078	12	4	25	JUNIN	JAUJA	PARCO	-11.3274352	-75.3006749999999982
1079	12	4	26	JUNIN	JAUJA	POMACANCHA	-11.3274352	-75.3006749999999982
1080	12	4	27	JUNIN	JAUJA	RICRAN	-11.3274352	-75.3006749999999982
1081	12	4	28	JUNIN	JAUJA	SAN LORENZO	-11.3274352	-75.3006749999999982
1082	12	4	29	JUNIN	JAUJA	SAN PEDRO DE CHUNAN	-11.3274352	-75.3006749999999982
1083	12	4	30	JUNIN	JAUJA	SAUSA	-11.3274352	-75.3006749999999982
1084	12	4	31	JUNIN	JAUJA	SINCOS	-11.3274352	-75.3006749999999982
1085	12	4	32	JUNIN	JAUJA	TUNAN MARCA	-11.3274352	-75.3006749999999982
1086	12	4	33	JUNIN	JAUJA	YAULI	-11.3274352	-75.3006749999999982
1087	12	4	34	JUNIN	JAUJA	YAUYOS	-11.3274352	-75.3006749999999982
1088	12	5	1	JUNIN	JUNIN	JUNIN	-11.3274352	-75.3006749999999982
1089	12	5	2	JUNIN	JUNIN	CARHUAMAYO	-11.3274352	-75.3006749999999982
1090	12	5	3	JUNIN	JUNIN	ONDORES	-11.3274352	-75.3006749999999982
1091	12	5	4	JUNIN	JUNIN	ULCUMAYO	-11.3274352	-75.3006749999999982
1092	12	6	1	JUNIN	SATIPO	SATIPO	-11.3274352	-75.3006749999999982
1093	12	6	2	JUNIN	SATIPO	COVIRIALI	-11.3274352	-75.3006749999999982
1094	12	6	3	JUNIN	SATIPO	LLAYLLA	-11.3274352	-75.3006749999999982
1095	12	6	4	JUNIN	SATIPO	MAZAMARI	-11.3274352	-75.3006749999999982
1096	12	6	5	JUNIN	SATIPO	PAMPA HERMOSA	-11.3274352	-75.3006749999999982
1097	12	6	6	JUNIN	SATIPO	PANGOA	-11.3274352	-75.3006749999999982
1098	12	6	7	JUNIN	SATIPO	RIO NEGRO	-11.3274352	-75.3006749999999982
1099	12	6	8	JUNIN	SATIPO	RIO TAMBO	-11.3274352	-75.3006749999999982
1100	12	7	1	JUNIN	TARMA	TARMA	-11.3274352	-75.3006749999999982
1101	12	7	2	JUNIN	TARMA	ACOBAMBA	-11.3274352	-75.3006749999999982
1102	12	7	3	JUNIN	TARMA	HUARICOLCA	-11.3274352	-75.3006749999999982
1103	12	7	4	JUNIN	TARMA	HUASAHUASI	-11.3274352	-75.3006749999999982
1104	12	7	5	JUNIN	TARMA	LA UNION	-11.3274352	-75.3006749999999982
1105	12	7	6	JUNIN	TARMA	PALCA	-11.3274352	-75.3006749999999982
1106	12	7	7	JUNIN	TARMA	PALCAMAYO	-11.3274352	-75.3006749999999982
1107	12	7	8	JUNIN	TARMA	SAN PEDRO DE CAJAS	-11.3274352	-75.3006749999999982
1108	12	7	9	JUNIN	TARMA	TAPO	-11.3274352	-75.3006749999999982
1109	12	8	1	JUNIN	YAULI	LA OROYA	-11.3274352	-75.3006749999999982
1110	12	8	2	JUNIN	YAULI	CHACAPALPA	-11.3274352	-75.3006749999999982
1111	12	8	3	JUNIN	YAULI	HUAY-HUAY	-11.3274352	-75.3006749999999982
1112	12	8	4	JUNIN	YAULI	MARCAPOMACOCHA	-11.3274352	-75.3006749999999982
1113	12	8	5	JUNIN	YAULI	MOROCOCHA	-11.3274352	-75.3006749999999982
1114	12	8	6	JUNIN	YAULI	PACCHA	-11.3274352	-75.3006749999999982
1115	12	8	7	JUNIN	YAULI	SANTA BARBARA DE CARHUACAYAN	-11.3274352	-75.3006749999999982
1116	12	8	8	JUNIN	YAULI	SANTA ROSA DE SACCO	-11.3274352	-75.3006749999999982
1117	12	8	9	JUNIN	YAULI	SUITUCANCHA	-11.3274352	-75.3006749999999982
1118	12	8	10	JUNIN	YAULI	YAULI	-11.3274352	-75.3006749999999982
1119	12	9	1	JUNIN	CHUPACA	CHUPACA	-11.3274352	-75.3006749999999982
1120	12	9	2	JUNIN	CHUPACA	AHUAC	-11.3274352	-75.3006749999999982
1121	12	9	3	JUNIN	CHUPACA	CHONGOS BAJO	-11.3274352	-75.3006749999999982
1122	12	9	4	JUNIN	CHUPACA	HUACHAC	-11.3274352	-75.3006749999999982
1123	12	9	5	JUNIN	CHUPACA	HUAMANCACA CHICO	-11.3274352	-75.3006749999999982
1124	12	9	6	JUNIN	CHUPACA	SAN JUAN DE YSCOS	-11.3274352	-75.3006749999999982
1125	12	9	7	JUNIN	CHUPACA	SAN JUAN DE JARPA	-11.3274352	-75.3006749999999982
1126	12	9	8	JUNIN	CHUPACA	TRES DE DICIEMBRE	-11.3274352	-75.3006749999999982
1127	12	9	9	JUNIN	CHUPACA	YANACANCHA	-11.3274352	-75.3006749999999982
1492	18	2	5	MOQUEGUA	GENERAL SANCHEZ CERRO	LA CAPILLA	-17.1938440999999997	-70.931410200000002
1493	18	2	6	MOQUEGUA	GENERAL SANCHEZ CERRO	LLOQUE	-17.1938440999999997	-70.931410200000002
1494	18	2	7	MOQUEGUA	GENERAL SANCHEZ CERRO	MATALAQUE	-17.1938440999999997	-70.931410200000002
1495	18	2	8	MOQUEGUA	GENERAL SANCHEZ CERRO	PUQUINA	-17.1938440999999997	-70.931410200000002
1496	18	2	9	MOQUEGUA	GENERAL SANCHEZ CERRO	QUINISTAQUILLAS	-17.1938440999999997	-70.931410200000002
1497	18	2	10	MOQUEGUA	GENERAL SANCHEZ CERRO	UBINAS	-17.1938440999999997	-70.931410200000002
192	2	14	4	ANCASH	OCROS	CARHUAPAMPA	-9.07503419999999927	-78.593697199999994
193	2	14	5	ANCASH	OCROS	COCHAS	-9.07503419999999927	-78.593697199999994
194	2	14	6	ANCASH	OCROS	CONGAS	-9.07503419999999927	-78.593697199999994
195	2	14	7	ANCASH	OCROS	LLIPA	-9.07503419999999927	-78.593697199999994
196	2	14	8	ANCASH	OCROS	SAN CRISTOBAL DE RAJAN	-9.07503419999999927	-78.593697199999994
197	2	14	9	ANCASH	OCROS	SAN PEDRO	-9.07503419999999927	-78.593697199999994
198	2	14	10	ANCASH	OCROS	SANTIAGO DE CHILCAS	-9.07503419999999927	-78.593697199999994
199	2	15	1	ANCASH	PALLASCA	CABANA	-9.07503419999999927	-78.593697199999994
200	2	15	2	ANCASH	PALLASCA	BOLOGNESI	-9.07503419999999927	-78.593697199999994
201	2	15	3	ANCASH	PALLASCA	CONCHUCOS	-9.07503419999999927	-78.593697199999994
202	2	15	4	ANCASH	PALLASCA	HUACASCHUQUE	-9.07503419999999927	-78.593697199999994
203	2	15	5	ANCASH	PALLASCA	HUANDOVAL	-9.07503419999999927	-78.593697199999994
204	2	15	6	ANCASH	PALLASCA	LACABAMBA	-9.07503419999999927	-78.593697199999994
205	2	15	7	ANCASH	PALLASCA	LLAPO	-9.07503419999999927	-78.593697199999994
206	2	15	8	ANCASH	PALLASCA	PALLASCA	-9.07503419999999927	-78.593697199999994
207	2	15	9	ANCASH	PALLASCA	PAMPAS	-9.07503419999999927	-78.593697199999994
208	2	15	10	ANCASH	PALLASCA	SANTA ROSA	-9.07503419999999927	-78.593697199999994
209	2	15	11	ANCASH	PALLASCA	TAUCA	-9.07503419999999927	-78.593697199999994
210	2	16	1	ANCASH	POMABAMBA	POMABAMBA	-9.07503419999999927	-78.593697199999994
211	2	16	2	ANCASH	POMABAMBA	HUAYLLAN	-9.07503419999999927	-78.593697199999994
212	2	16	3	ANCASH	POMABAMBA	PAROBAMBA	-9.07503419999999927	-78.593697199999994
213	2	16	4	ANCASH	POMABAMBA	QUINUABAMBA	-9.07503419999999927	-78.593697199999994
214	2	17	1	ANCASH	RECUAY	RECUAY	-9.07503419999999927	-78.593697199999994
215	2	17	2	ANCASH	RECUAY	CATAC	-9.07503419999999927	-78.593697199999994
216	2	17	3	ANCASH	RECUAY	COTAPARACO	-9.07503419999999927	-78.593697199999994
217	2	17	4	ANCASH	RECUAY	HUAYLLAPAMPA	-9.07503419999999927	-78.593697199999994
218	2	17	5	ANCASH	RECUAY	LLACLLIN	-9.07503419999999927	-78.593697199999994
219	2	17	6	ANCASH	RECUAY	MARCA	-9.07503419999999927	-78.593697199999994
220	2	17	7	ANCASH	RECUAY	PAMPAS CHICO	-9.07503419999999927	-78.593697199999994
221	2	17	8	ANCASH	RECUAY	PARARIN	-9.07503419999999927	-78.593697199999994
222	2	17	9	ANCASH	RECUAY	TAPACOCHA	-9.07503419999999927	-78.593697199999994
223	2	17	10	ANCASH	RECUAY	TICAPAMPA	-9.07503419999999927	-78.593697199999994
224	2	18	1	ANCASH	SANTA	CHIMBOTE	-9.07503419999999927	-78.593697199999994
225	2	18	2	ANCASH	SANTA	CACERES DEL PERU	-9.07503419999999927	-78.593697199999994
226	2	18	3	ANCASH	SANTA	COISHCO	-9.07503419999999927	-78.593697199999994
227	2	18	4	ANCASH	SANTA	MACATE	-9.07503419999999927	-78.593697199999994
228	2	18	5	ANCASH	SANTA	MORO	-9.07503419999999927	-78.593697199999994
229	2	18	6	ANCASH	SANTA	NEPEÑA	-9.07503419999999927	-78.593697199999994
230	2	18	7	ANCASH	SANTA	SAMANCO	-9.07503419999999927	-78.593697199999994
1498	18	2	11	MOQUEGUA	GENERAL SANCHEZ CERRO	YUNGA	-17.1938440999999997	-70.931410200000002
1500	18	3	2	MOQUEGUA	ILO	EL ALGARROBAL	-17.1938440999999997	-70.931410200000002
1501	18	3	3	MOQUEGUA	ILO	PACOCHA	-17.1938440999999997	-70.931410200000002
1421	16	1	2	LORETO	MAYNAS	ALTO NANAY	-3.93750549999999988	-75.3412179000000037
1422	16	1	3	LORETO	MAYNAS	FERNANDO LORES	-3.93750549999999988	-75.3412179000000037
1423	16	1	4	LORETO	MAYNAS	INDIANA	-3.93750549999999988	-75.3412179000000037
1424	16	1	5	LORETO	MAYNAS	LAS AMAZONAS	-3.93750549999999988	-75.3412179000000037
1425	16	1	6	LORETO	MAYNAS	MAZAN	-3.93750549999999988	-75.3412179000000037
1426	16	1	7	LORETO	MAYNAS	NAPO	-3.93750549999999988	-75.3412179000000037
1427	16	1	8	LORETO	MAYNAS	PUNCHANA	-3.93750549999999988	-75.3412179000000037
1428	16	1	9	LORETO	MAYNAS	PUTUMAYO	-3.93750549999999988	-75.3412179000000037
1429	16	1	10	LORETO	MAYNAS	TORRES CAUSANA	-3.93750549999999988	-75.3412179000000037
1430	16	1	12	LORETO	MAYNAS	BELEN	-3.93750549999999988	-75.3412179000000037
1431	16	1	13	LORETO	MAYNAS	SAN JUAN BAUTISTA	-3.93750549999999988	-75.3412179000000037
1432	16	1	14	LORETO	MAYNAS	TENIENTE MANUEL CLAVERO	-3.93750549999999988	-75.3412179000000037
1433	16	2	1	LORETO	ALTO AMAZONAS	YURIMAGUAS	-3.93750549999999988	-75.3412179000000037
1434	16	2	2	LORETO	ALTO AMAZONAS	BALSAPUERTO	-3.93750549999999988	-75.3412179000000037
1435	16	2	5	LORETO	ALTO AMAZONAS	JEBEROS	-3.93750549999999988	-75.3412179000000037
1456	16	5	9	LORETO	REQUENA	TAPICHE	-3.93750549999999988	-75.3412179000000037
1457	16	5	10	LORETO	REQUENA	JENARO HERRERA	-3.93750549999999988	-75.3412179000000037
1458	16	5	11	LORETO	REQUENA	YAQUERANA	-3.93750549999999988	-75.3412179000000037
1459	16	6	1	LORETO	UCAYALI	CONTAMANA	-3.93750549999999988	-75.3412179000000037
1460	16	6	2	LORETO	UCAYALI	INAHUAYA	-3.93750549999999988	-75.3412179000000037
1461	16	6	3	LORETO	UCAYALI	PADRE MARQUEZ	-3.93750549999999988	-75.3412179000000037
1462	16	6	4	LORETO	UCAYALI	PAMPA HERMOSA	-3.93750549999999988	-75.3412179000000037
1463	16	6	5	LORETO	UCAYALI	SARAYACU	-3.93750549999999988	-75.3412179000000037
1464	16	6	6	LORETO	UCAYALI	VARGAS GUERRA	-3.93750549999999988	-75.3412179000000037
1465	16	7	1	LORETO	DATEM DEL MARAÑON	BARRANCA	-3.93750549999999988	-75.3412179000000037
1466	16	7	2	LORETO	DATEM DEL MARAÑON	CAHUAPANAS	-3.93750549999999988	-75.3412179000000037
1467	16	7	3	LORETO	DATEM DEL MARAÑON	MANSERICHE	-3.93750549999999988	-75.3412179000000037
1468	16	7	4	LORETO	DATEM DEL MARAÑON	MORONA	-3.93750549999999988	-75.3412179000000037
1469	16	7	5	LORETO	DATEM DEL MARAÑON	PASTAZA	-3.93750549999999988	-75.3412179000000037
1470	16	7	6	LORETO	DATEM DEL MARAÑON	ANDOAS	-3.93750549999999988	-75.3412179000000037
1821	25	1	2	UCAYALI	CORONEL PORTILLO	CAMPOVERDE	-10.5166257999999999	-73.0877490000000023
1822	25	1	3	UCAYALI	CORONEL PORTILLO	IPARIA	-10.5166257999999999	-73.0877490000000023
1823	25	1	4	UCAYALI	CORONEL PORTILLO	MASISEA	-10.5166257999999999	-73.0877490000000023
1824	25	1	5	UCAYALI	CORONEL PORTILLO	YARINACOCHA	-10.5166257999999999	-73.0877490000000023
1825	25	1	6	UCAYALI	CORONEL PORTILLO	NUEVA REQUENA	-10.5166257999999999	-73.0877490000000023
1826	25	2	1	UCAYALI	ATALAYA	RAYMONDI	-10.5166257999999999	-73.0877490000000023
1827	25	2	2	UCAYALI	ATALAYA	SEPAHUA	-10.5166257999999999	-73.0877490000000023
1828	25	2	3	UCAYALI	ATALAYA	TAHUANIA	-10.5166257999999999	-73.0877490000000023
1829	25	2	4	UCAYALI	ATALAYA	YURUA	-10.5166257999999999	-73.0877490000000023
1830	25	3	1	UCAYALI	PADRE ABAD	PADRE ABAD	-10.5166257999999999	-73.0877490000000023
1831	25	3	2	UCAYALI	PADRE ABAD	IRAZOLA	-10.5166257999999999	-73.0877490000000023
1832	25	3	3	UCAYALI	PADRE ABAD	CURIMANA	-10.5166257999999999	-73.0877490000000023
1833	25	4	1	UCAYALI	PURUS	PURUS	-10.5166257999999999	-73.0877490000000023
792	9	1	1	HUANCAVELICA	HUANCAVELICA	HUANCAVELICA	-12.7862089000000001	-74.9766082999999952
793	9	1	2	HUANCAVELICA	HUANCAVELICA	ACOBAMBILLA	-12.7862089000000001	-74.9766082999999952
794	9	1	3	HUANCAVELICA	HUANCAVELICA	ACORIA	-12.7862089000000001	-74.9766082999999952
795	9	1	4	HUANCAVELICA	HUANCAVELICA	CONAYCA	-12.7862089000000001	-74.9766082999999952
796	9	1	5	HUANCAVELICA	HUANCAVELICA	CUENCA	-12.7862089000000001	-74.9766082999999952
797	9	1	6	HUANCAVELICA	HUANCAVELICA	HUACHOCOLPA	-12.7862089000000001	-74.9766082999999952
331	4	1	1	AREQUIPA	AREQUIPA	AREQUIPA	-16.3990141999999999	-71.5363660999999951
332	4	1	2	AREQUIPA	AREQUIPA	ALTO SELVA ALEGRE	-16.3990141999999999	-71.5363660999999951
333	4	1	3	AREQUIPA	AREQUIPA	CAYMA	-16.3990141999999999	-71.5363660999999951
334	4	1	4	AREQUIPA	AREQUIPA	CERRO COLORADO	-16.3990141999999999	-71.5363660999999951
335	4	1	5	AREQUIPA	AREQUIPA	CHARACATO	-16.3990141999999999	-71.5363660999999951
336	4	1	6	AREQUIPA	AREQUIPA	CHIGUATA	-16.3990141999999999	-71.5363660999999951
337	4	1	7	AREQUIPA	AREQUIPA	JACOBO HUNTER	-16.3990141999999999	-71.5363660999999951
338	4	1	8	AREQUIPA	AREQUIPA	LA JOYA	-16.3990141999999999	-71.5363660999999951
339	4	1	9	AREQUIPA	AREQUIPA	MARIANO MELGAR	-16.3990141999999999	-71.5363660999999951
340	4	1	10	AREQUIPA	AREQUIPA	MIRAFLORES	-16.3990141999999999	-71.5363660999999951
341	4	1	11	AREQUIPA	AREQUIPA	MOLLEBAYA	-16.3990141999999999	-71.5363660999999951
342	4	1	12	AREQUIPA	AREQUIPA	PAUCARPATA	-16.3990141999999999	-71.5363660999999951
343	4	1	13	AREQUIPA	AREQUIPA	POCSI	-16.3990141999999999	-71.5363660999999951
344	4	1	14	AREQUIPA	AREQUIPA	POLOBAYA	-16.3990141999999999	-71.5363660999999951
345	4	1	15	AREQUIPA	AREQUIPA	QUEQUEÑA	-16.3990141999999999	-71.5363660999999951
346	4	1	16	AREQUIPA	AREQUIPA	SABANDIA	-16.3990141999999999	-71.5363660999999951
347	4	1	17	AREQUIPA	AREQUIPA	SACHACA	-16.3990141999999999	-71.5363660999999951
348	4	1	18	AREQUIPA	AREQUIPA	SAN JUAN DE SIGUAS	-16.3990141999999999	-71.5363660999999951
349	4	1	19	AREQUIPA	AREQUIPA	SAN JUAN DE TARUCANI	-16.3990141999999999	-71.5363660999999951
350	4	1	20	AREQUIPA	AREQUIPA	SANTA ISABEL DE SIGUAS	-16.3990141999999999	-71.5363660999999951
351	4	1	21	AREQUIPA	AREQUIPA	SANTA RITA DE SIGUAS	-16.3990141999999999	-71.5363660999999951
352	4	1	22	AREQUIPA	AREQUIPA	SOCABAYA	-16.3990141999999999	-71.5363660999999951
353	4	1	23	AREQUIPA	AREQUIPA	TIABAYA	-16.3990141999999999	-71.5363660999999951
354	4	1	24	AREQUIPA	AREQUIPA	UCHUMAYO	-16.3990141999999999	-71.5363660999999951
355	4	1	25	AREQUIPA	AREQUIPA	VITOR	-16.3990141999999999	-71.5363660999999951
356	4	1	26	AREQUIPA	AREQUIPA	YANAHUARA	-16.3990141999999999	-71.5363660999999951
357	4	1	27	AREQUIPA	AREQUIPA	YARABAMBA	-16.3990141999999999	-71.5363660999999951
358	4	1	28	AREQUIPA	AREQUIPA	YURA	-16.3990141999999999	-71.5363660999999951
359	4	1	29	AREQUIPA	AREQUIPA	JOSE LUIS BUSTAMANTE Y RIVERO	-16.3990141999999999	-71.5363660999999951
360	4	2	1	AREQUIPA	CAMANA	CAMANA	-16.3990141999999999	-71.5363660999999951
361	4	2	2	AREQUIPA	CAMANA	JOSE MARIA QUIMPER	-16.3990141999999999	-71.5363660999999951
362	4	2	3	AREQUIPA	CAMANA	MARIANO NICOLAS VALCARCEL	-16.3990141999999999	-71.5363660999999951
363	4	2	4	AREQUIPA	CAMANA	MARISCAL CACERES	-16.3990141999999999	-71.5363660999999951
364	4	2	5	AREQUIPA	CAMANA	NICOLAS DE PIEROLA	-16.3990141999999999	-71.5363660999999951
365	4	2	6	AREQUIPA	CAMANA	OCOÑA	-16.3990141999999999	-71.5363660999999951
366	4	2	7	AREQUIPA	CAMANA	QUILCA	-16.3990141999999999	-71.5363660999999951
367	4	2	8	AREQUIPA	CAMANA	SAMUEL PASTOR	-16.3990141999999999	-71.5363660999999951
368	4	3	1	AREQUIPA	CARAVELI	CARAVELI	-16.3990141999999999	-71.5363660999999951
369	4	3	2	AREQUIPA	CARAVELI	ACARI	-16.3990141999999999	-71.5363660999999951
798	9	1	7	HUANCAVELICA	HUANCAVELICA	HUAYLLAHUARA	-12.7862089000000001	-74.9766082999999952
799	9	1	8	HUANCAVELICA	HUANCAVELICA	IZCUCHACA	-12.7862089000000001	-74.9766082999999952
800	9	1	9	HUANCAVELICA	HUANCAVELICA	LARIA	-12.7862089000000001	-74.9766082999999952
801	9	1	10	HUANCAVELICA	HUANCAVELICA	MANTA	-12.7862089000000001	-74.9766082999999952
802	9	1	11	HUANCAVELICA	HUANCAVELICA	MARISCAL CACERES	-12.7862089000000001	-74.9766082999999952
803	9	1	12	HUANCAVELICA	HUANCAVELICA	MOYA	-12.7862089000000001	-74.9766082999999952
804	9	1	13	HUANCAVELICA	HUANCAVELICA	NUEVO OCCORO	-12.7862089000000001	-74.9766082999999952
805	9	1	14	HUANCAVELICA	HUANCAVELICA	PALCA	-12.7862089000000001	-74.9766082999999952
806	9	1	15	HUANCAVELICA	HUANCAVELICA	PILCHACA	-12.7862089000000001	-74.9766082999999952
807	9	1	16	HUANCAVELICA	HUANCAVELICA	VILCA	-12.7862089000000001	-74.9766082999999952
808	9	1	17	HUANCAVELICA	HUANCAVELICA	YAULI	-12.7862089000000001	-74.9766082999999952
809	9	1	18	HUANCAVELICA	HUANCAVELICA	ASCENSION	-12.7862089000000001	-74.9766082999999952
810	9	1	19	HUANCAVELICA	HUANCAVELICA	HUANDO	-12.7862089000000001	-74.9766082999999952
811	9	2	1	HUANCAVELICA	ACOBAMBA	ACOBAMBA	-12.7862089000000001	-74.9766082999999952
812	9	2	2	HUANCAVELICA	ACOBAMBA	ANDABAMBA	-12.7862089000000001	-74.9766082999999952
813	9	2	3	HUANCAVELICA	ACOBAMBA	ANTA	-12.7862089000000001	-74.9766082999999952
814	9	2	4	HUANCAVELICA	ACOBAMBA	CAJA	-12.7862089000000001	-74.9766082999999952
815	9	2	5	HUANCAVELICA	ACOBAMBA	MARCAS	-12.7862089000000001	-74.9766082999999952
816	9	2	6	HUANCAVELICA	ACOBAMBA	PAUCARA	-12.7862089000000001	-74.9766082999999952
817	9	2	7	HUANCAVELICA	ACOBAMBA	POMACOCHA	-12.7862089000000001	-74.9766082999999952
818	9	2	8	HUANCAVELICA	ACOBAMBA	ROSARIO	-12.7862089000000001	-74.9766082999999952
819	9	3	1	HUANCAVELICA	ANGARAES	LIRCAY	-12.7862089000000001	-74.9766082999999952
820	9	3	2	HUANCAVELICA	ANGARAES	ANCHONGA	-12.7862089000000001	-74.9766082999999952
821	9	3	3	HUANCAVELICA	ANGARAES	CALLANMARCA	-12.7862089000000001	-74.9766082999999952
822	9	3	4	HUANCAVELICA	ANGARAES	CCOCHACCASA	-12.7862089000000001	-74.9766082999999952
823	9	3	5	HUANCAVELICA	ANGARAES	CHINCHO	-12.7862089000000001	-74.9766082999999952
824	9	3	6	HUANCAVELICA	ANGARAES	CONGALLA	-12.7862089000000001	-74.9766082999999952
825	9	3	7	HUANCAVELICA	ANGARAES	HUANCA-HUANCA	-12.7862089000000001	-74.9766082999999952
826	9	3	8	HUANCAVELICA	ANGARAES	HUAYLLAY GRANDE	-12.7862089000000001	-74.9766082999999952
827	9	3	9	HUANCAVELICA	ANGARAES	JULCAMARCA	-12.7862089000000001	-74.9766082999999952
828	9	3	10	HUANCAVELICA	ANGARAES	SAN ANTONIO DE ANTAPARCO	-12.7862089000000001	-74.9766082999999952
829	9	3	11	HUANCAVELICA	ANGARAES	SANTO TOMAS DE PATA	-12.7862089000000001	-74.9766082999999952
830	9	3	12	HUANCAVELICA	ANGARAES	SECCLLA	-12.7862089000000001	-74.9766082999999952
831	9	4	1	HUANCAVELICA	CASTROVIRREYNA	CASTROVIRREYNA	-12.7862089000000001	-74.9766082999999952
832	9	4	2	HUANCAVELICA	CASTROVIRREYNA	ARMA	-12.7862089000000001	-74.9766082999999952
833	9	4	3	HUANCAVELICA	CASTROVIRREYNA	AURAHUA	-12.7862089000000001	-74.9766082999999952
834	9	4	4	HUANCAVELICA	CASTROVIRREYNA	CAPILLAS	-12.7862089000000001	-74.9766082999999952
835	9	4	5	HUANCAVELICA	CASTROVIRREYNA	CHUPAMARCA	-12.7862089000000001	-74.9766082999999952
836	9	4	6	HUANCAVELICA	CASTROVIRREYNA	COCAS	-12.7862089000000001	-74.9766082999999952
837	9	4	7	HUANCAVELICA	CASTROVIRREYNA	HUACHOS	-12.7862089000000001	-74.9766082999999952
838	9	4	8	HUANCAVELICA	CASTROVIRREYNA	HUAMATAMBO	-12.7862089000000001	-74.9766082999999952
839	9	4	9	HUANCAVELICA	CASTROVIRREYNA	MOLLEPAMPA	-12.7862089000000001	-74.9766082999999952
840	9	4	10	HUANCAVELICA	CASTROVIRREYNA	SAN JUAN	-12.7862089000000001	-74.9766082999999952
841	9	4	11	HUANCAVELICA	CASTROVIRREYNA	SANTA ANA	-12.7862089000000001	-74.9766082999999952
842	9	4	12	HUANCAVELICA	CASTROVIRREYNA	TANTARA	-12.7862089000000001	-74.9766082999999952
843	9	4	13	HUANCAVELICA	CASTROVIRREYNA	TICRAPO	-12.7862089000000001	-74.9766082999999952
844	9	5	1	HUANCAVELICA	CHURCAMPA	CHURCAMPA	-12.7862089000000001	-74.9766082999999952
845	9	5	2	HUANCAVELICA	CHURCAMPA	ANCO	-12.7862089000000001	-74.9766082999999952
846	9	5	3	HUANCAVELICA	CHURCAMPA	CHINCHIHUASI	-12.7862089000000001	-74.9766082999999952
847	9	5	4	HUANCAVELICA	CHURCAMPA	EL CARMEN	-12.7862089000000001	-74.9766082999999952
848	9	5	5	HUANCAVELICA	CHURCAMPA	LA MERCED	-12.7862089000000001	-74.9766082999999952
849	9	5	6	HUANCAVELICA	CHURCAMPA	LOCROJA	-12.7862089000000001	-74.9766082999999952
711	8	4	4	CUSCO	CALCA	LARES	-13.5247492999999999	-71.9725027000000068
712	8	4	5	CUSCO	CALCA	PISAC	-13.5247492999999999	-71.9725027000000068
713	8	4	6	CUSCO	CALCA	SAN SALVADOR	-13.5247492999999999	-71.9725027000000068
714	8	4	7	CUSCO	CALCA	TARAY	-13.5247492999999999	-71.9725027000000068
715	8	4	8	CUSCO	CALCA	YANATILE	-13.5247492999999999	-71.9725027000000068
716	8	5	1	CUSCO	CANAS	YANAOCA	-13.5247492999999999	-71.9725027000000068
717	8	5	2	CUSCO	CANAS	CHECCA	-13.5247492999999999	-71.9725027000000068
718	8	5	3	CUSCO	CANAS	KUNTURKANKI	-13.5247492999999999	-71.9725027000000068
719	8	5	4	CUSCO	CANAS	LANGUI	-13.5247492999999999	-71.9725027000000068
720	8	5	5	CUSCO	CANAS	LAYO	-13.5247492999999999	-71.9725027000000068
721	8	5	6	CUSCO	CANAS	PAMPAMARCA	-13.5247492999999999	-71.9725027000000068
722	8	5	7	CUSCO	CANAS	QUEHUE	-13.5247492999999999	-71.9725027000000068
723	8	5	8	CUSCO	CANAS	TUPAC AMARU	-13.5247492999999999	-71.9725027000000068
724	8	6	1	CUSCO	CANCHIS	SICUANI	-13.5247492999999999	-71.9725027000000068
725	8	6	2	CUSCO	CANCHIS	CHECACUPE	-13.5247492999999999	-71.9725027000000068
726	8	6	3	CUSCO	CANCHIS	COMBAPATA	-13.5247492999999999	-71.9725027000000068
727	8	6	4	CUSCO	CANCHIS	MARANGANI	-13.5247492999999999	-71.9725027000000068
728	8	6	5	CUSCO	CANCHIS	PITUMARCA	-13.5247492999999999	-71.9725027000000068
729	8	6	6	CUSCO	CANCHIS	SAN PABLO	-13.5247492999999999	-71.9725027000000068
730	8	6	7	CUSCO	CANCHIS	SAN PEDRO	-13.5247492999999999	-71.9725027000000068
731	8	6	8	CUSCO	CANCHIS	TINTA	-13.5247492999999999	-71.9725027000000068
732	8	7	1	CUSCO	CHUMBIVILCAS	SANTO TOMAS	-13.5247492999999999	-71.9725027000000068
733	8	7	2	CUSCO	CHUMBIVILCAS	CAPACMARCA	-13.5247492999999999	-71.9725027000000068
734	8	7	3	CUSCO	CHUMBIVILCAS	CHAMACA	-13.5247492999999999	-71.9725027000000068
735	8	7	4	CUSCO	CHUMBIVILCAS	COLQUEMARCA	-13.5247492999999999	-71.9725027000000068
736	8	7	5	CUSCO	CHUMBIVILCAS	LIVITACA	-13.5247492999999999	-71.9725027000000068
737	8	7	6	CUSCO	CHUMBIVILCAS	LLUSCO	-13.5247492999999999	-71.9725027000000068
738	8	7	7	CUSCO	CHUMBIVILCAS	QUIÑOTA	-13.5247492999999999	-71.9725027000000068
739	8	7	8	CUSCO	CHUMBIVILCAS	VELILLE	-13.5247492999999999	-71.9725027000000068
740	8	8	1	CUSCO	ESPINAR	ESPINAR	-13.5247492999999999	-71.9725027000000068
741	8	8	2	CUSCO	ESPINAR	CONDOROMA	-13.5247492999999999	-71.9725027000000068
742	8	8	3	CUSCO	ESPINAR	COPORAQUE	-13.5247492999999999	-71.9725027000000068
743	8	8	4	CUSCO	ESPINAR	OCORURO	-13.5247492999999999	-71.9725027000000068
744	8	8	5	CUSCO	ESPINAR	PALLPATA	-13.5247492999999999	-71.9725027000000068
745	8	8	6	CUSCO	ESPINAR	PICHIGUA	-13.5247492999999999	-71.9725027000000068
746	8	8	7	CUSCO	ESPINAR	SUYCKUTAMBO	-13.5247492999999999	-71.9725027000000068
747	8	8	8	CUSCO	ESPINAR	ALTO PICHIGUA	-13.5247492999999999	-71.9725027000000068
748	8	9	1	CUSCO	LA CONVENCION	SANTA ANA	-13.5247492999999999	-71.9725027000000068
749	8	9	2	CUSCO	LA CONVENCION	ECHARATE	-13.5247492999999999	-71.9725027000000068
750	8	9	3	CUSCO	LA CONVENCION	HUAYOPATA	-13.5247492999999999	-71.9725027000000068
751	8	9	4	CUSCO	LA CONVENCION	MARANURA	-13.5247492999999999	-71.9725027000000068
752	8	9	5	CUSCO	LA CONVENCION	OCOBAMBA	-13.5247492999999999	-71.9725027000000068
753	8	9	6	CUSCO	LA CONVENCION	QUELLOUNO	-13.5247492999999999	-71.9725027000000068
754	8	9	7	CUSCO	LA CONVENCION	KIMBIRI	-13.5247492999999999	-71.9725027000000068
755	8	9	8	CUSCO	LA CONVENCION	SANTA TERESA	-13.5247492999999999	-71.9725027000000068
756	8	9	9	CUSCO	LA CONVENCION	VILCABAMBA	-13.5247492999999999	-71.9725027000000068
757	8	9	10	CUSCO	LA CONVENCION	PICHARI	-13.5247492999999999	-71.9725027000000068
758	8	10	1	CUSCO	PARURO	PARURO	-13.5247492999999999	-71.9725027000000068
759	8	10	2	CUSCO	PARURO	ACCHA	-13.5247492999999999	-71.9725027000000068
760	8	10	3	CUSCO	PARURO	CCAPI	-13.5247492999999999	-71.9725027000000068
761	8	10	4	CUSCO	PARURO	COLCHA	-13.5247492999999999	-71.9725027000000068
762	8	10	5	CUSCO	PARURO	HUANOQUITE	-13.5247492999999999	-71.9725027000000068
1264	15	1	16	LIMA	LIMA	LINCE	-12.0478053999999997	-77.0625787000000031
1265	15	1	17	LIMA	LIMA	LOS OLIVOS	-12.0478053999999997	-77.0625787000000031
1266	15	1	18	LIMA	LIMA	LURIGANCHO	-12.0478053999999997	-77.0625787000000031
1267	15	1	19	LIMA	LIMA	LURIN	-12.0478053999999997	-77.0625787000000031
1268	15	1	20	LIMA	LIMA	MAGDALENA DEL MAR	-12.0478053999999997	-77.0625787000000031
1269	15	1	21	LIMA	LIMA	MAGDALENA VIEJA	-12.0478053999999997	-77.0625787000000031
1270	15	1	22	LIMA	LIMA	MIRAFLORES	-12.0478053999999997	-77.0625787000000031
1271	15	1	23	LIMA	LIMA	PACHACAMAC	-12.0478053999999997	-77.0625787000000031
1272	15	1	24	LIMA	LIMA	PUCUSANA	-12.0478053999999997	-77.0625787000000031
1273	15	1	25	LIMA	LIMA	PUENTE PIEDRA	-12.0478053999999997	-77.0625787000000031
1274	15	1	26	LIMA	LIMA	PUNTA HERMOSA	-12.0478053999999997	-77.0625787000000031
1275	15	1	27	LIMA	LIMA	PUNTA NEGRA	-12.0478053999999997	-77.0625787000000031
1276	15	1	28	LIMA	LIMA	RIMAC	-12.0478053999999997	-77.0625787000000031
1277	15	1	29	LIMA	LIMA	SAN BARTOLO	-12.0478053999999997	-77.0625787000000031
1278	15	1	30	LIMA	LIMA	SAN BORJA	-12.0478053999999997	-77.0625787000000031
1279	15	1	31	LIMA	LIMA	SAN ISIDRO	-12.0478053999999997	-77.0625787000000031
1280	15	1	32	LIMA	LIMA	SAN JUAN DE LURIGANCHO	-12.0478053999999997	-77.0625787000000031
1281	15	1	33	LIMA	LIMA	SAN JUAN DE MIRAFLORES	-12.0478053999999997	-77.0625787000000031
1282	15	1	34	LIMA	LIMA	SAN LUIS	-12.0478053999999997	-77.0625787000000031
1283	15	1	35	LIMA	LIMA	SAN MARTIN DE PORRES	-12.0478053999999997	-77.0625787000000031
1284	15	1	36	LIMA	LIMA	SAN MIGUEL	-12.0478053999999997	-77.0625787000000031
1285	15	1	37	LIMA	LIMA	SANTA ANITA	-12.0478053999999997	-77.0625787000000031
1286	15	1	38	LIMA	LIMA	SANTA MARIA DEL MAR	-12.0478053999999997	-77.0625787000000031
1287	15	1	39	LIMA	LIMA	SANTA ROSA	-12.0478053999999997	-77.0625787000000031
1288	15	1	40	LIMA	LIMA	SANTIAGO DE SURCO	-12.0478053999999997	-77.0625787000000031
1289	15	1	41	LIMA	LIMA	SURQUILLO	-12.0478053999999997	-77.0625787000000031
1290	15	1	42	LIMA	LIMA	VILLA EL SALVADOR	-12.0478053999999997	-77.0625787000000031
1291	15	1	43	LIMA	LIMA	VILLA MARIA DEL TRIUNFO	-12.0478053999999997	-77.0625787000000031
1292	15	2	1	LIMA	BARRANCA	BARRANCA	-12.0478053999999997	-77.0625787000000031
1293	15	2	2	LIMA	BARRANCA	PARAMONGA	-12.0478053999999997	-77.0625787000000031
1294	15	2	3	LIMA	BARRANCA	PATIVILCA	-12.0478053999999997	-77.0625787000000031
1295	15	2	4	LIMA	BARRANCA	SUPE	-12.0478053999999997	-77.0625787000000031
1296	15	2	5	LIMA	BARRANCA	SUPE PUERTO	-12.0478053999999997	-77.0625787000000031
1297	15	3	1	LIMA	CAJATAMBO	CAJATAMBO	-12.0478053999999997	-77.0625787000000031
1298	15	3	2	LIMA	CAJATAMBO	COPA	-12.0478053999999997	-77.0625787000000031
1299	15	3	3	LIMA	CAJATAMBO	GORGOR	-12.0478053999999997	-77.0625787000000031
1300	15	3	4	LIMA	CAJATAMBO	HUANCAPON	-12.0478053999999997	-77.0625787000000031
1301	15	3	5	LIMA	CAJATAMBO	MANAS	-12.0478053999999997	-77.0625787000000031
1302	15	4	1	LIMA	CANTA	CANTA	-12.0478053999999997	-77.0625787000000031
1303	15	4	2	LIMA	CANTA	ARAHUAY	-12.0478053999999997	-77.0625787000000031
1304	15	4	3	LIMA	CANTA	HUAMANTANGA	-12.0478053999999997	-77.0625787000000031
1305	15	4	4	LIMA	CANTA	HUAROS	-12.0478053999999997	-77.0625787000000031
1306	15	4	5	LIMA	CANTA	LACHAQUI	-12.0478053999999997	-77.0625787000000031
1307	15	4	6	LIMA	CANTA	SAN BUENAVENTURA	-12.0478053999999997	-77.0625787000000031
1308	15	4	7	LIMA	CANTA	SANTA ROSA DE QUIVES	-12.0478053999999997	-77.0625787000000031
1309	15	5	1	LIMA	CAÑETE	SAN VICENTE DE CAÑETE	-12.0478053999999997	-77.0625787000000031
1310	15	5	2	LIMA	CAÑETE	ASIA	-12.0478053999999997	-77.0625787000000031
1311	15	5	3	LIMA	CAÑETE	CALANGO	-12.0478053999999997	-77.0625787000000031
1312	15	5	4	LIMA	CAÑETE	CERRO AZUL	-12.0478053999999997	-77.0625787000000031
1313	15	5	5	LIMA	CAÑETE	CHILCA	-12.0478053999999997	-77.0625787000000031
1314	15	5	6	LIMA	CAÑETE	COAYLLO	-12.0478053999999997	-77.0625787000000031
1315	15	5	7	LIMA	CAÑETE	IMPERIAL	-12.0478053999999997	-77.0625787000000031
1316	15	5	8	LIMA	CAÑETE	LUNAHUANA	-12.0478053999999997	-77.0625787000000031
1317	15	5	9	LIMA	CAÑETE	MALA	-12.0478053999999997	-77.0625787000000031
1318	15	5	10	LIMA	CAÑETE	NUEVO IMPERIAL	-12.0478053999999997	-77.0625787000000031
1319	15	5	11	LIMA	CAÑETE	PACARAN	-12.0478053999999997	-77.0625787000000031
1320	15	5	12	LIMA	CAÑETE	QUILMANA	-12.0478053999999997	-77.0625787000000031
1321	15	5	13	LIMA	CAÑETE	SAN ANTONIO	-12.0478053999999997	-77.0625787000000031
1322	15	5	14	LIMA	CAÑETE	SAN LUIS	-12.0478053999999997	-77.0625787000000031
1323	15	5	15	LIMA	CAÑETE	SANTA CRUZ DE FLORES	-12.0478053999999997	-77.0625787000000031
1324	15	5	16	LIMA	CAÑETE	ZUÑIGA	-12.0478053999999997	-77.0625787000000031
579	6	4	1	CAJAMARCA	CHOTA	CHOTA	-7.16246730000000031	-78.5103021999999982
1325	15	6	1	LIMA	HUARAL	HUARAL	-12.0478053999999997	-77.0625787000000031
1326	15	6	2	LIMA	HUARAL	ATAVILLOS ALTO	-12.0478053999999997	-77.0625787000000031
1327	15	6	3	LIMA	HUARAL	ATAVILLOS BAJO	-12.0478053999999997	-77.0625787000000031
1328	15	6	4	LIMA	HUARAL	AUCALLAMA	-12.0478053999999997	-77.0625787000000031
1329	15	6	5	LIMA	HUARAL	CHANCAY	-12.0478053999999997	-77.0625787000000031
1330	15	6	6	LIMA	HUARAL	IHUARI	-12.0478053999999997	-77.0625787000000031
1331	15	6	7	LIMA	HUARAL	LAMPIAN	-12.0478053999999997	-77.0625787000000031
684	8	1	1	CUSCO	CUSCO	CUSCO	-13.5247492999999999	-71.9725027000000068
685	8	1	2	CUSCO	CUSCO	CCORCA	-13.5247492999999999	-71.9725027000000068
686	8	1	3	CUSCO	CUSCO	POROY	-13.5247492999999999	-71.9725027000000068
687	8	1	4	CUSCO	CUSCO	SAN JERONIMO	-13.5247492999999999	-71.9725027000000068
688	8	1	5	CUSCO	CUSCO	SAN SEBASTIAN	-13.5247492999999999	-71.9725027000000068
689	8	1	6	CUSCO	CUSCO	SANTIAGO	-13.5247492999999999	-71.9725027000000068
690	8	1	7	CUSCO	CUSCO	SAYLLA	-13.5247492999999999	-71.9725027000000068
691	8	1	8	CUSCO	CUSCO	WANCHAQ	-13.5247492999999999	-71.9725027000000068
692	8	2	1	CUSCO	ACOMAYO	ACOMAYO	-13.5247492999999999	-71.9725027000000068
693	8	2	2	CUSCO	ACOMAYO	ACOPIA	-13.5247492999999999	-71.9725027000000068
694	8	2	3	CUSCO	ACOMAYO	ACOS	-13.5247492999999999	-71.9725027000000068
695	8	2	4	CUSCO	ACOMAYO	MOSOC LLACTA	-13.5247492999999999	-71.9725027000000068
696	8	2	5	CUSCO	ACOMAYO	POMACANCHI	-13.5247492999999999	-71.9725027000000068
697	8	2	6	CUSCO	ACOMAYO	RONDOCAN	-13.5247492999999999	-71.9725027000000068
698	8	2	7	CUSCO	ACOMAYO	SANGARARA	-13.5247492999999999	-71.9725027000000068
699	8	3	1	CUSCO	ANTA	ANTA	-13.5247492999999999	-71.9725027000000068
1332	15	6	8	LIMA	HUARAL	PACARAOS	-12.0478053999999997	-77.0625787000000031
1333	15	6	9	LIMA	HUARAL	SAN MIGUEL DE ACOS	-12.0478053999999997	-77.0625787000000031
1334	15	6	10	LIMA	HUARAL	SANTA CRUZ DE ANDAMARCA	-12.0478053999999997	-77.0625787000000031
1335	15	6	11	LIMA	HUARAL	SUMBILCA	-12.0478053999999997	-77.0625787000000031
1336	15	6	12	LIMA	HUARAL	VEINTISIETE DE NOVIEMBRE	-12.0478053999999997	-77.0625787000000031
1352	15	7	16	LIMA	HUAROCHIRI	SAN ANTONIO	-12.0478053999999997	-77.0625787000000031
1353	15	7	17	LIMA	HUAROCHIRI	SAN BARTOLOME	-12.0478053999999997	-77.0625787000000031
1354	15	7	18	LIMA	HUAROCHIRI	SAN DAMIAN	-12.0478053999999997	-77.0625787000000031
1355	15	7	19	LIMA	HUAROCHIRI	SAN JUAN DE IRIS	-12.0478053999999997	-77.0625787000000031
1356	15	7	20	LIMA	HUAROCHIRI	SAN JUAN DE TANTARANCHE	-12.0478053999999997	-77.0625787000000031
1357	15	7	21	LIMA	HUAROCHIRI	SAN LORENZO DE QUINTI	-12.0478053999999997	-77.0625787000000031
1358	15	7	22	LIMA	HUAROCHIRI	SAN MATEO	-12.0478053999999997	-77.0625787000000031
1359	15	7	23	LIMA	HUAROCHIRI	SAN MATEO DE OTAO	-12.0478053999999997	-77.0625787000000031
1360	15	7	24	LIMA	HUAROCHIRI	SAN PEDRO DE CASTA	-12.0478053999999997	-77.0625787000000031
1361	15	7	25	LIMA	HUAROCHIRI	SAN PEDRO DE HUANCAYRE	-12.0478053999999997	-77.0625787000000031
1362	15	7	26	LIMA	HUAROCHIRI	SANGALLAYA	-12.0478053999999997	-77.0625787000000031
1363	15	7	27	LIMA	HUAROCHIRI	SANTA CRUZ DE COCACHACRA	-12.0478053999999997	-77.0625787000000031
1364	15	7	28	LIMA	HUAROCHIRI	SANTA EULALIA	-12.0478053999999997	-77.0625787000000031
1365	15	7	29	LIMA	HUAROCHIRI	SANTIAGO DE ANCHUCAYA	-12.0478053999999997	-77.0625787000000031
1366	15	7	30	LIMA	HUAROCHIRI	SANTIAGO DE TUNA	-12.0478053999999997	-77.0625787000000031
1367	15	7	31	LIMA	HUAROCHIRI	SANTO DOMINGO DE LOS OLLEROS	-12.0478053999999997	-77.0625787000000031
1368	15	7	32	LIMA	HUAROCHIRI	SURCO	-12.0478053999999997	-77.0625787000000031
1369	15	8	1	LIMA	HUAURA	HUACHO	-12.0478053999999997	-77.0625787000000031
1370	15	8	2	LIMA	HUAURA	AMBAR	-12.0478053999999997	-77.0625787000000031
1371	15	8	3	LIMA	HUAURA	CALETA DE CARQUIN	-12.0478053999999997	-77.0625787000000031
1372	15	8	4	LIMA	HUAURA	CHECRAS	-12.0478053999999997	-77.0625787000000031
1373	15	8	5	LIMA	HUAURA	HUALMAY	-12.0478053999999997	-77.0625787000000031
1374	15	8	6	LIMA	HUAURA	HUAURA	-12.0478053999999997	-77.0625787000000031
1375	15	8	7	LIMA	HUAURA	LEONCIO PRADO	-12.0478053999999997	-77.0625787000000031
1376	15	8	8	LIMA	HUAURA	PACCHO	-12.0478053999999997	-77.0625787000000031
1377	15	8	9	LIMA	HUAURA	SANTA LEONOR	-12.0478053999999997	-77.0625787000000031
1378	15	8	10	LIMA	HUAURA	SANTA MARIA	-12.0478053999999997	-77.0625787000000031
1379	15	8	11	LIMA	HUAURA	SAYAN	-12.0478053999999997	-77.0625787000000031
1380	15	8	12	LIMA	HUAURA	VEGUETA	-12.0478053999999997	-77.0625787000000031
1381	15	9	1	LIMA	OYON	OYON	-12.0478053999999997	-77.0625787000000031
1382	15	9	2	LIMA	OYON	ANDAJES	-12.0478053999999997	-77.0625787000000031
1383	15	9	3	LIMA	OYON	CAUJUL	-12.0478053999999997	-77.0625787000000031
1384	15	9	4	LIMA	OYON	COCHAMARCA	-12.0478053999999997	-77.0625787000000031
1385	15	9	5	LIMA	OYON	NAVAN	-12.0478053999999997	-77.0625787000000031
1386	15	9	6	LIMA	OYON	PACHANGARA	-12.0478053999999997	-77.0625787000000031
1387	15	10	1	LIMA	YAUYOS	YAUYOS	-12.0478053999999997	-77.0625787000000031
1388	15	10	2	LIMA	YAUYOS	ALIS	-12.0478053999999997	-77.0625787000000031
1389	15	10	3	LIMA	YAUYOS	AYAUCA	-12.0478053999999997	-77.0625787000000031
1390	15	10	4	LIMA	YAUYOS	AYAVIRI	-12.0478053999999997	-77.0625787000000031
1391	15	10	5	LIMA	YAUYOS	AZANGARO	-12.0478053999999997	-77.0625787000000031
1392	15	10	6	LIMA	YAUYOS	CACRA	-12.0478053999999997	-77.0625787000000031
1393	15	10	7	LIMA	YAUYOS	CARANIA	-12.0478053999999997	-77.0625787000000031
1394	15	10	8	LIMA	YAUYOS	CATAHUASI	-12.0478053999999997	-77.0625787000000031
1395	15	10	9	LIMA	YAUYOS	CHOCOS	-12.0478053999999997	-77.0625787000000031
1396	15	10	10	LIMA	YAUYOS	COCHAS	-12.0478053999999997	-77.0625787000000031
1397	15	10	11	LIMA	YAUYOS	COLONIA	-12.0478053999999997	-77.0625787000000031
1398	15	10	12	LIMA	YAUYOS	HONGOS	-12.0478053999999997	-77.0625787000000031
1399	15	10	13	LIMA	YAUYOS	HUAMPARA	-12.0478053999999997	-77.0625787000000031
1400	15	10	14	LIMA	YAUYOS	HUANCAYA	-12.0478053999999997	-77.0625787000000031
1401	15	10	15	LIMA	YAUYOS	HUANGASCAR	-12.0478053999999997	-77.0625787000000031
1402	15	10	16	LIMA	YAUYOS	HUANTAN	-12.0478053999999997	-77.0625787000000031
1403	15	10	17	LIMA	YAUYOS	HUAÑEC	-12.0478053999999997	-77.0625787000000031
1404	15	10	18	LIMA	YAUYOS	LARAOS	-12.0478053999999997	-77.0625787000000031
1405	15	10	19	LIMA	YAUYOS	LINCHA	-12.0478053999999997	-77.0625787000000031
1406	15	10	20	LIMA	YAUYOS	MADEAN	-12.0478053999999997	-77.0625787000000031
1407	15	10	21	LIMA	YAUYOS	MIRAFLORES	-12.0478053999999997	-77.0625787000000031
1408	15	10	22	LIMA	YAUYOS	OMAS	-12.0478053999999997	-77.0625787000000031
1409	15	10	23	LIMA	YAUYOS	PUTINZA	-12.0478053999999997	-77.0625787000000031
1410	15	10	24	LIMA	YAUYOS	QUINCHES	-12.0478053999999997	-77.0625787000000031
1411	15	10	25	LIMA	YAUYOS	QUINOCAY	-12.0478053999999997	-77.0625787000000031
1412	15	10	26	LIMA	YAUYOS	SAN JOAQUIN	-12.0478053999999997	-77.0625787000000031
1413	15	10	27	LIMA	YAUYOS	SAN PEDRO DE PILAS	-12.0478053999999997	-77.0625787000000031
1414	15	10	28	LIMA	YAUYOS	TANTA	-12.0478053999999997	-77.0625787000000031
1415	15	10	29	LIMA	YAUYOS	TAURIPAMPA	-12.0478053999999997	-77.0625787000000031
1416	15	10	30	LIMA	YAUYOS	TOMAS	-12.0478053999999997	-77.0625787000000031
1417	15	10	31	LIMA	YAUYOS	TUPE	-12.0478053999999997	-77.0625787000000031
1418	15	10	32	LIMA	YAUYOS	VIÑAC	-12.0478053999999997	-77.0625787000000031
1419	15	10	33	LIMA	YAUYOS	VITIS	-12.0478053999999997	-77.0625787000000031
678	7	1	1	CALLAO	CALLAO	CALLAO	-12.0340933000000003	-77.1378476000000006
763	8	10	6	CUSCO	PARURO	OMACHA	-13.5247492999999999	-71.9725027000000068
764	8	10	7	CUSCO	PARURO	PACCARITAMBO	-13.5247492999999999	-71.9725027000000068
765	8	10	8	CUSCO	PARURO	PILLPINTO	-13.5247492999999999	-71.9725027000000068
766	8	10	9	CUSCO	PARURO	YAURISQUE	-13.5247492999999999	-71.9725027000000068
767	8	11	1	CUSCO	PAUCARTAMBO	PAUCARTAMBO	-13.5247492999999999	-71.9725027000000068
768	8	11	2	CUSCO	PAUCARTAMBO	CAICAY	-13.5247492999999999	-71.9725027000000068
769	8	11	3	CUSCO	PAUCARTAMBO	CHALLABAMBA	-13.5247492999999999	-71.9725027000000068
770	8	11	4	CUSCO	PAUCARTAMBO	COLQUEPATA	-13.5247492999999999	-71.9725027000000068
771	8	11	5	CUSCO	PAUCARTAMBO	HUANCARANI	-13.5247492999999999	-71.9725027000000068
772	8	11	6	CUSCO	PAUCARTAMBO	KOSÑIPATA	-13.5247492999999999	-71.9725027000000068
773	8	12	1	CUSCO	QUISPICANCHI	URCOS	-13.5247492999999999	-71.9725027000000068
774	8	12	2	CUSCO	QUISPICANCHI	ANDAHUAYLILLAS	-13.5247492999999999	-71.9725027000000068
775	8	12	3	CUSCO	QUISPICANCHI	CAMANTI	-13.5247492999999999	-71.9725027000000068
776	8	12	4	CUSCO	QUISPICANCHI	CCARHUAYO	-13.5247492999999999	-71.9725027000000068
777	8	12	5	CUSCO	QUISPICANCHI	CCATCA	-13.5247492999999999	-71.9725027000000068
778	8	12	6	CUSCO	QUISPICANCHI	CUSIPATA	-13.5247492999999999	-71.9725027000000068
779	8	12	7	CUSCO	QUISPICANCHI	HUARO	-13.5247492999999999	-71.9725027000000068
780	8	12	8	CUSCO	QUISPICANCHI	LUCRE	-13.5247492999999999	-71.9725027000000068
781	8	12	9	CUSCO	QUISPICANCHI	MARCAPATA	-13.5247492999999999	-71.9725027000000068
782	8	12	10	CUSCO	QUISPICANCHI	OCONGATE	-13.5247492999999999	-71.9725027000000068
783	8	12	11	CUSCO	QUISPICANCHI	OROPESA	-13.5247492999999999	-71.9725027000000068
784	8	12	12	CUSCO	QUISPICANCHI	QUIQUIJANA	-13.5247492999999999	-71.9725027000000068
785	8	13	1	CUSCO	URUBAMBA	URUBAMBA	-13.5247492999999999	-71.9725027000000068
786	8	13	2	CUSCO	URUBAMBA	CHINCHERO	-13.5247492999999999	-71.9725027000000068
787	8	13	3	CUSCO	URUBAMBA	HUAYLLABAMBA	-13.5247492999999999	-71.9725027000000068
788	8	13	4	CUSCO	URUBAMBA	MACHUPICCHU	-13.5247492999999999	-71.9725027000000068
789	8	13	5	CUSCO	URUBAMBA	MARAS	-13.5247492999999999	-71.9725027000000068
790	8	13	6	CUSCO	URUBAMBA	OLLANTAYTAMBO	-13.5247492999999999	-71.9725027000000068
791	8	13	7	CUSCO	URUBAMBA	YUCAY	-13.5247492999999999	-71.9725027000000068
921	10	5	4	HUANUCO	HUAMALIES	JACAS GRANDE	-9.92975610000000053	-76.2392746000000017
922	10	5	5	HUANUCO	HUAMALIES	JIRCAN	-9.92975610000000053	-76.2392746000000017
923	10	5	6	HUANUCO	HUAMALIES	MIRAFLORES	-9.92975610000000053	-76.2392746000000017
924	10	5	7	HUANUCO	HUAMALIES	MONZON	-9.92975610000000053	-76.2392746000000017
925	10	5	8	HUANUCO	HUAMALIES	PUNCHAO	-9.92975610000000053	-76.2392746000000017
926	10	5	9	HUANUCO	HUAMALIES	PUÑOS	-9.92975610000000053	-76.2392746000000017
927	10	5	10	HUANUCO	HUAMALIES	SINGA	-9.92975610000000053	-76.2392746000000017
928	10	5	11	HUANUCO	HUAMALIES	TANTAMAYO	-9.92975610000000053	-76.2392746000000017
929	10	6	1	HUANUCO	LEONCIO PRADO	RUPA-RUPA	-9.92975610000000053	-76.2392746000000017
930	10	6	2	HUANUCO	LEONCIO PRADO	DANIEL ALOMIAS ROBLES	-9.92975610000000053	-76.2392746000000017
931	10	6	3	HUANUCO	LEONCIO PRADO	HERMILIO VALDIZAN	-9.92975610000000053	-76.2392746000000017
932	10	6	4	HUANUCO	LEONCIO PRADO	JOSE CRESPO Y CASTILLO	-9.92975610000000053	-76.2392746000000017
933	10	6	5	HUANUCO	LEONCIO PRADO	LUYANDO	-9.92975610000000053	-76.2392746000000017
886	10	1	1	HUANUCO	HUANUCO	HUANUCO	-9.92975610000000053	-76.2392746000000017
887	10	1	2	HUANUCO	HUANUCO	AMARILIS	-9.92975610000000053	-76.2392746000000017
888	10	1	3	HUANUCO	HUANUCO	CHINCHAO	-9.92975610000000053	-76.2392746000000017
889	10	1	4	HUANUCO	HUANUCO	CHURUBAMBA	-9.92975610000000053	-76.2392746000000017
890	10	1	5	HUANUCO	HUANUCO	MARGOS	-9.92975610000000053	-76.2392746000000017
891	10	1	6	HUANUCO	HUANUCO	QUISQUI	-9.92975610000000053	-76.2392746000000017
892	10	1	7	HUANUCO	HUANUCO	SAN FRANCISCO DE CAYRAN	-9.92975610000000053	-76.2392746000000017
893	10	1	8	HUANUCO	HUANUCO	SAN PEDRO DE CHAULAN	-9.92975610000000053	-76.2392746000000017
894	10	1	9	HUANUCO	HUANUCO	SANTA MARIA DEL VALLE	-9.92975610000000053	-76.2392746000000017
895	10	1	10	HUANUCO	HUANUCO	YARUMAYO	-9.92975610000000053	-76.2392746000000017
896	10	1	11	HUANUCO	HUANUCO	PILLCO MARCA	-9.92975610000000053	-76.2392746000000017
897	10	2	1	HUANUCO	AMBO	AMBO	-9.92975610000000053	-76.2392746000000017
898	10	2	2	HUANUCO	AMBO	CAYNA	-9.92975610000000053	-76.2392746000000017
899	10	2	3	HUANUCO	AMBO	COLPAS	-9.92975610000000053	-76.2392746000000017
900	10	2	4	HUANUCO	AMBO	CONCHAMARCA	-9.92975610000000053	-76.2392746000000017
901	10	2	5	HUANUCO	AMBO	HUACAR	-9.92975610000000053	-76.2392746000000017
902	10	2	6	HUANUCO	AMBO	SAN FRANCISCO	-9.92975610000000053	-76.2392746000000017
903	10	2	7	HUANUCO	AMBO	SAN RAFAEL	-9.92975610000000053	-76.2392746000000017
904	10	2	8	HUANUCO	AMBO	TOMAY KICHWA	-9.92975610000000053	-76.2392746000000017
905	10	3	1	HUANUCO	DOS DE MAYO	LA UNION	-9.92975610000000053	-76.2392746000000017
906	10	3	7	HUANUCO	DOS DE MAYO	CHUQUIS	-9.92975610000000053	-76.2392746000000017
907	10	3	11	HUANUCO	DOS DE MAYO	MARIAS	-9.92975610000000053	-76.2392746000000017
908	10	3	13	HUANUCO	DOS DE MAYO	PACHAS	-9.92975610000000053	-76.2392746000000017
909	10	3	16	HUANUCO	DOS DE MAYO	QUIVILLA	-9.92975610000000053	-76.2392746000000017
910	10	3	17	HUANUCO	DOS DE MAYO	RIPAN	-9.92975610000000053	-76.2392746000000017
911	10	3	21	HUANUCO	DOS DE MAYO	SHUNQUI	-9.92975610000000053	-76.2392746000000017
912	10	3	22	HUANUCO	DOS DE MAYO	SILLAPATA	-9.92975610000000053	-76.2392746000000017
913	10	3	23	HUANUCO	DOS DE MAYO	YANAS	-9.92975610000000053	-76.2392746000000017
914	10	4	1	HUANUCO	HUACAYBAMBA	HUACAYBAMBA	-9.92975610000000053	-76.2392746000000017
915	10	4	2	HUANUCO	HUACAYBAMBA	CANCHABAMBA	-9.92975610000000053	-76.2392746000000017
916	10	4	3	HUANUCO	HUACAYBAMBA	COCHABAMBA	-9.92975610000000053	-76.2392746000000017
917	10	4	4	HUANUCO	HUACAYBAMBA	PINRA	-9.92975610000000053	-76.2392746000000017
918	10	5	1	HUANUCO	HUAMALIES	LLATA	-9.92975610000000053	-76.2392746000000017
919	10	5	2	HUANUCO	HUAMALIES	ARANCAY	-9.92975610000000053	-76.2392746000000017
920	10	5	3	HUANUCO	HUAMALIES	CHAVIN DE PARIARCA	-9.92975610000000053	-76.2392746000000017
934	10	6	6	HUANUCO	LEONCIO PRADO	MARIANO DAMASO BERAUN	-9.92975610000000053	-76.2392746000000017
935	10	7	1	HUANUCO	MARAÑON	HUACRACHUCO	-9.92975610000000053	-76.2392746000000017
936	10	7	2	HUANUCO	MARAÑON	CHOLON	-9.92975610000000053	-76.2392746000000017
937	10	7	3	HUANUCO	MARAÑON	SAN BUENAVENTURA	-9.92975610000000053	-76.2392746000000017
938	10	8	1	HUANUCO	PACHITEA	PANAO	-9.92975610000000053	-76.2392746000000017
939	10	8	2	HUANUCO	PACHITEA	CHAGLLA	-9.92975610000000053	-76.2392746000000017
940	10	8	3	HUANUCO	PACHITEA	MOLINO	-9.92975610000000053	-76.2392746000000017
941	10	8	4	HUANUCO	PACHITEA	UMARI	-9.92975610000000053	-76.2392746000000017
942	10	9	1	HUANUCO	PUERTO INCA	PUERTO INCA	-9.92975610000000053	-76.2392746000000017
943	10	9	2	HUANUCO	PUERTO INCA	CODO DEL POZUZO	-9.92975610000000053	-76.2392746000000017
944	10	9	3	HUANUCO	PUERTO INCA	HONORIA	-9.92975610000000053	-76.2392746000000017
945	10	9	4	HUANUCO	PUERTO INCA	TOURNAVISTA	-9.92975610000000053	-76.2392746000000017
946	10	9	5	HUANUCO	PUERTO INCA	YUYAPICHIS	-9.92975610000000053	-76.2392746000000017
947	10	10	1	HUANUCO	LAURICOCHA	JESUS	-9.92975610000000053	-76.2392746000000017
948	10	10	2	HUANUCO	LAURICOCHA	BAÑOS	-9.92975610000000053	-76.2392746000000017
949	10	10	3	HUANUCO	LAURICOCHA	JIVIA	-9.92975610000000053	-76.2392746000000017
950	10	10	4	HUANUCO	LAURICOCHA	QUEROPALCA	-9.92975610000000053	-76.2392746000000017
951	10	10	5	HUANUCO	LAURICOCHA	RONDOS	-9.92975610000000053	-76.2392746000000017
952	10	10	6	HUANUCO	LAURICOCHA	SAN FRANCISCO DE ASIS	-9.92975610000000053	-76.2392746000000017
953	10	10	7	HUANUCO	LAURICOCHA	SAN MIGUEL DE CAURI	-9.92975610000000053	-76.2392746000000017
954	10	11	1	HUANUCO	YAROWILCA	CHAVINILLO	-9.92975610000000053	-76.2392746000000017
955	10	11	2	HUANUCO	YAROWILCA	CAHUAC	-9.92975610000000053	-76.2392746000000017
1005	12	1	1	JUNIN	HUANCAYO	HUANCAYO	-11.3274352	-75.3006749999999982
1006	12	1	4	JUNIN	HUANCAYO	CARHUACALLANGA	-11.3274352	-75.3006749999999982
1007	12	1	5	JUNIN	HUANCAYO	CHACAPAMPA	-11.3274352	-75.3006749999999982
1008	12	1	6	JUNIN	HUANCAYO	CHICCHE	-11.3274352	-75.3006749999999982
1009	12	1	7	JUNIN	HUANCAYO	CHILCA	-11.3274352	-75.3006749999999982
1010	12	1	8	JUNIN	HUANCAYO	CHONGOS ALTO	-11.3274352	-75.3006749999999982
1011	12	1	11	JUNIN	HUANCAYO	CHUPURO	-11.3274352	-75.3006749999999982
1012	12	1	12	JUNIN	HUANCAYO	COLCA	-11.3274352	-75.3006749999999982
1013	12	1	13	JUNIN	HUANCAYO	CULLHUAS	-11.3274352	-75.3006749999999982
1014	12	1	14	JUNIN	HUANCAYO	EL TAMBO	-11.3274352	-75.3006749999999982
1015	12	1	16	JUNIN	HUANCAYO	HUACRAPUQUIO	-11.3274352	-75.3006749999999982
1016	12	1	17	JUNIN	HUANCAYO	HUALHUAS	-11.3274352	-75.3006749999999982
1017	12	1	19	JUNIN	HUANCAYO	HUANCAN	-11.3274352	-75.3006749999999982
1018	12	1	20	JUNIN	HUANCAYO	HUASICANCHA	-11.3274352	-75.3006749999999982
1019	12	1	21	JUNIN	HUANCAYO	HUAYUCACHI	-11.3274352	-75.3006749999999982
1020	12	1	22	JUNIN	HUANCAYO	INGENIO	-11.3274352	-75.3006749999999982
1021	12	1	24	JUNIN	HUANCAYO	PARIAHUANCA	-11.3274352	-75.3006749999999982
1022	12	1	25	JUNIN	HUANCAYO	PILCOMAYO	-11.3274352	-75.3006749999999982
1023	12	1	26	JUNIN	HUANCAYO	PUCARA	-11.3274352	-75.3006749999999982
1024	12	1	27	JUNIN	HUANCAYO	QUICHUAY	-11.3274352	-75.3006749999999982
1025	12	1	28	JUNIN	HUANCAYO	QUILCAS	-11.3274352	-75.3006749999999982
1026	12	1	29	JUNIN	HUANCAYO	SAN AGUSTIN	-11.3274352	-75.3006749999999982
1027	12	1	30	JUNIN	HUANCAYO	SAN JERONIMO DE TUNAN	-11.3274352	-75.3006749999999982
1028	12	1	32	JUNIN	HUANCAYO	SAÑO	-11.3274352	-75.3006749999999982
1029	12	1	33	JUNIN	HUANCAYO	SAPALLANGA	-11.3274352	-75.3006749999999982
1030	12	1	34	JUNIN	HUANCAYO	SICAYA	-11.3274352	-75.3006749999999982
1031	12	1	35	JUNIN	HUANCAYO	SANTO DOMINGO DE ACOBAMBA	-11.3274352	-75.3006749999999982
1032	12	1	36	JUNIN	HUANCAYO	VIQUES	-11.3274352	-75.3006749999999982
1033	12	2	1	JUNIN	CONCEPCION	CONCEPCION	-11.3274352	-75.3006749999999982
1034	12	2	2	JUNIN	CONCEPCION	ACO	-11.3274352	-75.3006749999999982
1035	12	2	3	JUNIN	CONCEPCION	ANDAMARCA	-11.3274352	-75.3006749999999982
1036	12	2	4	JUNIN	CONCEPCION	CHAMBARA	-11.3274352	-75.3006749999999982
1037	12	2	5	JUNIN	CONCEPCION	COCHAS	-11.3274352	-75.3006749999999982
1038	12	2	6	JUNIN	CONCEPCION	COMAS	-11.3274352	-75.3006749999999982
1039	12	2	7	JUNIN	CONCEPCION	HEROINAS TOLEDO	-11.3274352	-75.3006749999999982
1040	12	2	8	JUNIN	CONCEPCION	MANZANARES	-11.3274352	-75.3006749999999982
1041	12	2	9	JUNIN	CONCEPCION	MARISCAL CASTILLA	-11.3274352	-75.3006749999999982
1042	12	2	10	JUNIN	CONCEPCION	MATAHUASI	-11.3274352	-75.3006749999999982
1043	12	2	11	JUNIN	CONCEPCION	MITO	-11.3274352	-75.3006749999999982
1045	12	2	13	JUNIN	CONCEPCION	ORCOTUNA	-11.3274352	-75.3006749999999982
1046	12	2	14	JUNIN	CONCEPCION	SAN JOSE DE QUERO	-11.3274352	-75.3006749999999982
1047	12	2	15	JUNIN	CONCEPCION	SANTA ROSA DE OCOPA	-11.3274352	-75.3006749999999982
1048	12	3	1	JUNIN	CHANCHAMAYO	CHANCHAMAYO	-11.3274352	-75.3006749999999982
1049	12	3	2	JUNIN	CHANCHAMAYO	PERENE	-11.3274352	-75.3006749999999982
1050	12	3	3	JUNIN	CHANCHAMAYO	PICHANAQUI	-11.3274352	-75.3006749999999982
1051	12	3	4	JUNIN	CHANCHAMAYO	SAN LUIS DE SHUARO	-11.3274352	-75.3006749999999982
1052	12	3	5	JUNIN	CHANCHAMAYO	SAN RAMON	-11.3274352	-75.3006749999999982
1053	12	3	6	JUNIN	CHANCHAMAYO	VITOC	-11.3274352	-75.3006749999999982
1054	12	4	1	JUNIN	JAUJA	JAUJA	-11.3274352	-75.3006749999999982
1055	12	4	2	JUNIN	JAUJA	ACOLLA	-11.3274352	-75.3006749999999982
1056	12	4	3	JUNIN	JAUJA	APATA	-11.3274352	-75.3006749999999982
1057	12	4	4	JUNIN	JAUJA	ATAURA	-11.3274352	-75.3006749999999982
1058	12	4	5	JUNIN	JAUJA	CANCHAYLLO	-11.3274352	-75.3006749999999982
1059	12	4	6	JUNIN	JAUJA	CURICACA	-11.3274352	-75.3006749999999982
1060	12	4	7	JUNIN	JAUJA	EL MANTARO	-11.3274352	-75.3006749999999982
1061	12	4	8	JUNIN	JAUJA	HUAMALI	-11.3274352	-75.3006749999999982
850	9	5	7	HUANCAVELICA	CHURCAMPA	PAUCARBAMBA	-12.7862089000000001	-74.9766082999999952
851	9	5	8	HUANCAVELICA	CHURCAMPA	SAN MIGUEL DE MAYOCC	-12.7862089000000001	-74.9766082999999952
852	9	5	9	HUANCAVELICA	CHURCAMPA	SAN PEDRO DE CORIS	-12.7862089000000001	-74.9766082999999952
853	9	5	10	HUANCAVELICA	CHURCAMPA	PACHAMARCA	-12.7862089000000001	-74.9766082999999952
854	9	6	1	HUANCAVELICA	HUAYTARA	HUAYTARA	-12.7862089000000001	-74.9766082999999952
855	9	6	2	HUANCAVELICA	HUAYTARA	AYAVI	-12.7862089000000001	-74.9766082999999952
856	9	6	3	HUANCAVELICA	HUAYTARA	CORDOVA	-12.7862089000000001	-74.9766082999999952
857	9	6	4	HUANCAVELICA	HUAYTARA	HUAYACUNDO ARMA	-12.7862089000000001	-74.9766082999999952
858	9	6	5	HUANCAVELICA	HUAYTARA	LARAMARCA	-12.7862089000000001	-74.9766082999999952
859	9	6	6	HUANCAVELICA	HUAYTARA	OCOYO	-12.7862089000000001	-74.9766082999999952
860	9	6	7	HUANCAVELICA	HUAYTARA	PILPICHACA	-12.7862089000000001	-74.9766082999999952
861	9	6	8	HUANCAVELICA	HUAYTARA	QUERCO	-12.7862089000000001	-74.9766082999999952
862	9	6	9	HUANCAVELICA	HUAYTARA	QUITO-ARMA	-12.7862089000000001	-74.9766082999999952
863	9	6	10	HUANCAVELICA	HUAYTARA	SAN ANTONIO DE CUSICANCHA	-12.7862089000000001	-74.9766082999999952
864	9	6	11	HUANCAVELICA	HUAYTARA	SAN FRANCISCO DE SANGAYAICO	-12.7862089000000001	-74.9766082999999952
865	9	6	12	HUANCAVELICA	HUAYTARA	SAN ISIDRO	-12.7862089000000001	-74.9766082999999952
866	9	6	13	HUANCAVELICA	HUAYTARA	SANTIAGO DE CHOCORVOS	-12.7862089000000001	-74.9766082999999952
867	9	6	14	HUANCAVELICA	HUAYTARA	SANTIAGO DE QUIRAHUARA	-12.7862089000000001	-74.9766082999999952
868	9	6	15	HUANCAVELICA	HUAYTARA	SANTO DOMINGO DE CAPILLAS	-12.7862089000000001	-74.9766082999999952
869	9	6	16	HUANCAVELICA	HUAYTARA	TAMBO	-12.7862089000000001	-74.9766082999999952
870	9	7	1	HUANCAVELICA	TAYACAJA	PAMPAS	-12.7862089000000001	-74.9766082999999952
871	9	7	2	HUANCAVELICA	TAYACAJA	ACOSTAMBO	-12.7862089000000001	-74.9766082999999952
872	9	7	3	HUANCAVELICA	TAYACAJA	ACRAQUIA	-12.7862089000000001	-74.9766082999999952
873	9	7	4	HUANCAVELICA	TAYACAJA	AHUAYCHA	-12.7862089000000001	-74.9766082999999952
874	9	7	5	HUANCAVELICA	TAYACAJA	COLCABAMBA	-12.7862089000000001	-74.9766082999999952
875	9	7	6	HUANCAVELICA	TAYACAJA	DANIEL HERNANDEZ	-12.7862089000000001	-74.9766082999999952
876	9	7	7	HUANCAVELICA	TAYACAJA	HUACHOCOLPA	-12.7862089000000001	-74.9766082999999952
877	9	7	9	HUANCAVELICA	TAYACAJA	HUARIBAMBA	-12.7862089000000001	-74.9766082999999952
878	9	7	10	HUANCAVELICA	TAYACAJA	ÑAHUIMPUQUIO	-12.7862089000000001	-74.9766082999999952
879	9	7	11	HUANCAVELICA	TAYACAJA	PAZOS	-12.7862089000000001	-74.9766082999999952
880	9	7	13	HUANCAVELICA	TAYACAJA	QUISHUAR	-12.7862089000000001	-74.9766082999999952
881	9	7	14	HUANCAVELICA	TAYACAJA	SALCABAMBA	-12.7862089000000001	-74.9766082999999952
882	9	7	15	HUANCAVELICA	TAYACAJA	SALCAHUASI	-12.7862089000000001	-74.9766082999999952
883	9	7	16	HUANCAVELICA	TAYACAJA	SAN MARCOS DE ROCCHAC	-12.7862089000000001	-74.9766082999999952
884	9	7	17	HUANCAVELICA	TAYACAJA	SURCUBAMBA	-12.7862089000000001	-74.9766082999999952
885	9	7	18	HUANCAVELICA	TAYACAJA	TINTAY PUNCU	-12.7862089000000001	-74.9766082999999952
551	6	1	1	CAJAMARCA	CAJAMARCA	CAJAMARCA	-7.16246730000000031	-78.5103021999999982
552	6	1	2	CAJAMARCA	CAJAMARCA	ASUNCION	-7.16246730000000031	-78.5103021999999982
553	6	1	3	CAJAMARCA	CAJAMARCA	CHETILLA	-7.16246730000000031	-78.5103021999999982
554	6	1	4	CAJAMARCA	CAJAMARCA	COSPAN	-7.16246730000000031	-78.5103021999999982
555	6	1	5	CAJAMARCA	CAJAMARCA	ENCAÑADA	-7.16246730000000031	-78.5103021999999982
556	6	1	6	CAJAMARCA	CAJAMARCA	JESUS	-7.16246730000000031	-78.5103021999999982
557	6	1	7	CAJAMARCA	CAJAMARCA	LLACANORA	-7.16246730000000031	-78.5103021999999982
558	6	1	8	CAJAMARCA	CAJAMARCA	LOS BAÑOS DEL INCA	-7.16246730000000031	-78.5103021999999982
559	6	1	9	CAJAMARCA	CAJAMARCA	MAGDALENA	-7.16246730000000031	-78.5103021999999982
560	6	1	10	CAJAMARCA	CAJAMARCA	MATARA	-7.16246730000000031	-78.5103021999999982
561	6	1	11	CAJAMARCA	CAJAMARCA	NAMORA	-7.16246730000000031	-78.5103021999999982
562	6	1	12	CAJAMARCA	CAJAMARCA	SAN JUAN	-7.16246730000000031	-78.5103021999999982
563	6	2	1	CAJAMARCA	CAJABAMBA	CAJABAMBA	-7.16246730000000031	-78.5103021999999982
564	6	2	2	CAJAMARCA	CAJABAMBA	CACHACHI	-7.16246730000000031	-78.5103021999999982
565	6	2	3	CAJAMARCA	CAJABAMBA	CONDEBAMBA	-7.16246730000000031	-78.5103021999999982
566	6	2	4	CAJAMARCA	CAJABAMBA	SITACOCHA	-7.16246730000000031	-78.5103021999999982
567	6	3	1	CAJAMARCA	CELENDIN	CELENDIN	-7.16246730000000031	-78.5103021999999982
568	6	3	2	CAJAMARCA	CELENDIN	CHUMUCH	-7.16246730000000031	-78.5103021999999982
569	6	3	3	CAJAMARCA	CELENDIN	CORTEGANA	-7.16246730000000031	-78.5103021999999982
570	6	3	4	CAJAMARCA	CELENDIN	HUASMIN	-7.16246730000000031	-78.5103021999999982
571	6	3	5	CAJAMARCA	CELENDIN	JORGE CHAVEZ	-7.16246730000000031	-78.5103021999999982
572	6	3	6	CAJAMARCA	CELENDIN	JOSE GALVEZ	-7.16246730000000031	-78.5103021999999982
573	6	3	7	CAJAMARCA	CELENDIN	MIGUEL IGLESIAS	-7.16246730000000031	-78.5103021999999982
574	6	3	8	CAJAMARCA	CELENDIN	OXAMARCA	-7.16246730000000031	-78.5103021999999982
575	6	3	9	CAJAMARCA	CELENDIN	SOROCHUCO	-7.16246730000000031	-78.5103021999999982
576	6	3	10	CAJAMARCA	CELENDIN	SUCRE	-7.16246730000000031	-78.5103021999999982
577	6	3	11	CAJAMARCA	CELENDIN	UTCO	-7.16246730000000031	-78.5103021999999982
578	6	3	12	CAJAMARCA	CELENDIN	LA LIBERTAD DE PALLAN	-7.16246730000000031	-78.5103021999999982
580	6	4	2	CAJAMARCA	CHOTA	ANGUIA	-7.16246730000000031	-78.5103021999999982
581	6	4	3	CAJAMARCA	CHOTA	CHADIN	-7.16246730000000031	-78.5103021999999982
582	6	4	4	CAJAMARCA	CHOTA	CHIGUIRIP	-7.16246730000000031	-78.5103021999999982
583	6	4	5	CAJAMARCA	CHOTA	CHIMBAN	-7.16246730000000031	-78.5103021999999982
584	6	4	6	CAJAMARCA	CHOTA	CHOROPAMPA	-7.16246730000000031	-78.5103021999999982
585	6	4	7	CAJAMARCA	CHOTA	COCHABAMBA	-7.16246730000000031	-78.5103021999999982
586	6	4	8	CAJAMARCA	CHOTA	CONCHAN	-7.16246730000000031	-78.5103021999999982
1211	14	1	1	LAMBAYEQUE	CHICLAYO	CHICLAYO	-6.50080159999999996	-79.9180869000000058
1212	14	1	2	LAMBAYEQUE	CHICLAYO	CHONGOYAPE	-6.50080159999999996	-79.9180869000000058
1213	14	1	3	LAMBAYEQUE	CHICLAYO	ETEN	-6.50080159999999996	-79.9180869000000058
1214	14	1	4	LAMBAYEQUE	CHICLAYO	ETEN PUERTO	-6.50080159999999996	-79.9180869000000058
1215	14	1	5	LAMBAYEQUE	CHICLAYO	JOSE LEONARDO ORTIZ	-6.50080159999999996	-79.9180869000000058
1216	14	1	6	LAMBAYEQUE	CHICLAYO	LA VICTORIA	-6.50080159999999996	-79.9180869000000058
1217	14	1	7	LAMBAYEQUE	CHICLAYO	LAGUNAS	-6.50080159999999996	-79.9180869000000058
1218	14	1	8	LAMBAYEQUE	CHICLAYO	MONSEFU	-6.50080159999999996	-79.9180869000000058
1219	14	1	9	LAMBAYEQUE	CHICLAYO	NUEVA ARICA	-6.50080159999999996	-79.9180869000000058
1220	14	1	10	LAMBAYEQUE	CHICLAYO	OYOTUN	-6.50080159999999996	-79.9180869000000058
1221	14	1	11	LAMBAYEQUE	CHICLAYO	PICSI	-6.50080159999999996	-79.9180869000000058
1222	14	1	12	LAMBAYEQUE	CHICLAYO	PIMENTEL	-6.50080159999999996	-79.9180869000000058
1223	14	1	13	LAMBAYEQUE	CHICLAYO	REQUE	-6.50080159999999996	-79.9180869000000058
1224	14	1	14	LAMBAYEQUE	CHICLAYO	SANTA ROSA	-6.50080159999999996	-79.9180869000000058
1225	14	1	15	LAMBAYEQUE	CHICLAYO	SAÑA	-6.50080159999999996	-79.9180869000000058
1226	14	1	16	LAMBAYEQUE	CHICLAYO	CAYALTI	-6.50080159999999996	-79.9180869000000058
1227	14	1	17	LAMBAYEQUE	CHICLAYO	PATAPO	-6.50080159999999996	-79.9180869000000058
1228	14	1	18	LAMBAYEQUE	CHICLAYO	POMALCA	-6.50080159999999996	-79.9180869000000058
1229	14	1	19	LAMBAYEQUE	CHICLAYO	PUCALA	-6.50080159999999996	-79.9180869000000058
1230	14	1	20	LAMBAYEQUE	CHICLAYO	TUMAN	-6.50080159999999996	-79.9180869000000058
1231	14	2	1	LAMBAYEQUE	FERREÑAFE	FERREÑAFE	-6.50080159999999996	-79.9180869000000058
1232	14	2	2	LAMBAYEQUE	FERREÑAFE	CAÑARIS	-6.50080159999999996	-79.9180869000000058
1233	14	2	3	LAMBAYEQUE	FERREÑAFE	INCAHUASI	-6.50080159999999996	-79.9180869000000058
1234	14	2	4	LAMBAYEQUE	FERREÑAFE	MANUEL ANTONIO MESONES MURO	-6.50080159999999996	-79.9180869000000058
1235	14	2	5	LAMBAYEQUE	FERREÑAFE	PITIPO	-6.50080159999999996	-79.9180869000000058
587	6	4	9	CAJAMARCA	CHOTA	HUAMBOS	-7.16246730000000031	-78.5103021999999982
588	6	4	10	CAJAMARCA	CHOTA	LAJAS	-7.16246730000000031	-78.5103021999999982
589	6	4	11	CAJAMARCA	CHOTA	LLAMA	-7.16246730000000031	-78.5103021999999982
590	6	4	12	CAJAMARCA	CHOTA	MIRACOSTA	-7.16246730000000031	-78.5103021999999982
591	6	4	13	CAJAMARCA	CHOTA	PACCHA	-7.16246730000000031	-78.5103021999999982
592	6	4	14	CAJAMARCA	CHOTA	PION	-7.16246730000000031	-78.5103021999999982
593	6	4	15	CAJAMARCA	CHOTA	QUEROCOTO	-7.16246730000000031	-78.5103021999999982
594	6	4	16	CAJAMARCA	CHOTA	SAN JUAN DE LICUPIS	-7.16246730000000031	-78.5103021999999982
595	6	4	17	CAJAMARCA	CHOTA	TACABAMBA	-7.16246730000000031	-78.5103021999999982
596	6	4	18	CAJAMARCA	CHOTA	TOCMOCHE	-7.16246730000000031	-78.5103021999999982
597	6	4	19	CAJAMARCA	CHOTA	CHALAMARCA	-7.16246730000000031	-78.5103021999999982
598	6	5	1	CAJAMARCA	CONTUMAZA	CONTUMAZA	-7.16246730000000031	-78.5103021999999982
599	6	5	2	CAJAMARCA	CONTUMAZA	CHILETE	-7.16246730000000031	-78.5103021999999982
600	6	5	3	CAJAMARCA	CONTUMAZA	CUPISNIQUE	-7.16246730000000031	-78.5103021999999982
601	6	5	4	CAJAMARCA	CONTUMAZA	GUZMANGO	-7.16246730000000031	-78.5103021999999982
602	6	5	5	CAJAMARCA	CONTUMAZA	SAN BENITO	-7.16246730000000031	-78.5103021999999982
603	6	5	6	CAJAMARCA	CONTUMAZA	SANTA CRUZ DE TOLED	-7.16246730000000031	-78.5103021999999982
604	6	5	7	CAJAMARCA	CONTUMAZA	TANTARICA	-7.16246730000000031	-78.5103021999999982
605	6	5	8	CAJAMARCA	CONTUMAZA	YONAN	-7.16246730000000031	-78.5103021999999982
606	6	6	1	CAJAMARCA	CUTERVO	CUTERVO	-7.16246730000000031	-78.5103021999999982
607	6	6	2	CAJAMARCA	CUTERVO	CALLAYUC	-7.16246730000000031	-78.5103021999999982
608	6	6	3	CAJAMARCA	CUTERVO	CHOROS	-7.16246730000000031	-78.5103021999999982
609	6	6	4	CAJAMARCA	CUTERVO	CUJILLO	-7.16246730000000031	-78.5103021999999982
610	6	6	5	CAJAMARCA	CUTERVO	LA RAMADA	-7.16246730000000031	-78.5103021999999982
611	6	6	6	CAJAMARCA	CUTERVO	PIMPINGOS	-7.16246730000000031	-78.5103021999999982
612	6	6	7	CAJAMARCA	CUTERVO	QUEROCOTILLO	-7.16246730000000031	-78.5103021999999982
613	6	6	8	CAJAMARCA	CUTERVO	SAN ANDRES DE CUTERVO	-7.16246730000000031	-78.5103021999999982
614	6	6	9	CAJAMARCA	CUTERVO	SAN JUAN DE CUTERVO	-7.16246730000000031	-78.5103021999999982
615	6	6	10	CAJAMARCA	CUTERVO	SAN LUIS DE LUCMA	-7.16246730000000031	-78.5103021999999982
616	6	6	11	CAJAMARCA	CUTERVO	SANTA CRUZ	-7.16246730000000031	-78.5103021999999982
617	6	6	12	CAJAMARCA	CUTERVO	SANTO DOMINGO DE LA CAPILLA	-7.16246730000000031	-78.5103021999999982
618	6	6	13	CAJAMARCA	CUTERVO	SANTO TOMAS	-7.16246730000000031	-78.5103021999999982
619	6	6	14	CAJAMARCA	CUTERVO	SOCOTA	-7.16246730000000031	-78.5103021999999982
620	6	6	15	CAJAMARCA	CUTERVO	TORIBIO CASANOVA	-7.16246730000000031	-78.5103021999999982
621	6	7	1	CAJAMARCA	HUALGAYOC	BAMBAMARCA	-7.16246730000000031	-78.5103021999999982
622	6	7	2	CAJAMARCA	HUALGAYOC	CHUGUR	-7.16246730000000031	-78.5103021999999982
623	6	7	3	CAJAMARCA	HUALGAYOC	HUALGAYOC	-7.16246730000000031	-78.5103021999999982
624	6	8	1	CAJAMARCA	JAEN	JAEN	-7.16246730000000031	-78.5103021999999982
625	6	8	2	CAJAMARCA	JAEN	BELLAVISTA	-7.16246730000000031	-78.5103021999999982
626	6	8	3	CAJAMARCA	JAEN	CHONTALI	-7.16246730000000031	-78.5103021999999982
627	6	8	4	CAJAMARCA	JAEN	COLASAY	-7.16246730000000031	-78.5103021999999982
628	6	8	5	CAJAMARCA	JAEN	HUABAL	-7.16246730000000031	-78.5103021999999982
629	6	8	6	CAJAMARCA	JAEN	LAS PIRIAS	-7.16246730000000031	-78.5103021999999982
956	10	11	3	HUANUCO	YAROWILCA	CHACABAMBA	-9.92975610000000053	-76.2392746000000017
957	10	11	4	HUANUCO	YAROWILCA	APARICIO POMARES	-9.92975610000000053	-76.2392746000000017
958	10	11	5	HUANUCO	YAROWILCA	JACAS CHICO	-9.92975610000000053	-76.2392746000000017
959	10	11	6	HUANUCO	YAROWILCA	OBAS	-9.92975610000000053	-76.2392746000000017
960	10	11	7	HUANUCO	YAROWILCA	PAMPAMARCA	-9.92975610000000053	-76.2392746000000017
961	10	11	8	HUANUCO	YAROWILCA	CHORAS	-9.92975610000000053	-76.2392746000000017
1703	22	1	1	SAN MARTIN	MOYOBAMBA	MOYOBAMBA	-7.26717749999999985	-76.7336892999999947
1704	22	1	2	SAN MARTIN	MOYOBAMBA	CALZADA	-7.26717749999999985	-76.7336892999999947
1705	22	1	3	SAN MARTIN	MOYOBAMBA	HABANA	-7.26717749999999985	-76.7336892999999947
1706	22	1	4	SAN MARTIN	MOYOBAMBA	JEPELACIO	-7.26717749999999985	-76.7336892999999947
1707	22	1	5	SAN MARTIN	MOYOBAMBA	SORITOR	-7.26717749999999985	-76.7336892999999947
1708	22	1	6	SAN MARTIN	MOYOBAMBA	YANTALO	-7.26717749999999985	-76.7336892999999947
1709	22	2	1	SAN MARTIN	BELLAVISTA	BELLAVISTA	-7.26717749999999985	-76.7336892999999947
1710	22	2	2	SAN MARTIN	BELLAVISTA	ALTO BIAVO	-7.26717749999999985	-76.7336892999999947
1711	22	2	3	SAN MARTIN	BELLAVISTA	BAJO BIAVO	-7.26717749999999985	-76.7336892999999947
1712	22	2	4	SAN MARTIN	BELLAVISTA	HUALLAGA	-7.26717749999999985	-76.7336892999999947
1713	22	2	5	SAN MARTIN	BELLAVISTA	SAN PABLO	-7.26717749999999985	-76.7336892999999947
1714	22	2	6	SAN MARTIN	BELLAVISTA	SAN RAFAEL	-7.26717749999999985	-76.7336892999999947
1715	22	3	1	SAN MARTIN	EL DORADO	SAN JOSE DE SISA	-7.26717749999999985	-76.7336892999999947
1716	22	3	2	SAN MARTIN	EL DORADO	AGUA BLANCA	-7.26717749999999985	-76.7336892999999947
1717	22	3	3	SAN MARTIN	EL DORADO	SAN MARTIN	-7.26717749999999985	-76.7336892999999947
1718	22	3	4	SAN MARTIN	EL DORADO	SANTA ROSA	-7.26717749999999985	-76.7336892999999947
1719	22	3	5	SAN MARTIN	EL DORADO	SHATOJA	-7.26717749999999985	-76.7336892999999947
1720	22	4	1	SAN MARTIN	HUALLAGA	SAPOSOA	-7.26717749999999985	-76.7336892999999947
1249	15	1	1	LIMA	LIMA	LIMA	-12.0478053999999997	-77.0625787000000031
1250	15	1	2	LIMA	LIMA	ANCON	-12.0478053999999997	-77.0625787000000031
1251	15	1	3	LIMA	LIMA	ATE	-12.0478053999999997	-77.0625787000000031
1252	15	1	4	LIMA	LIMA	BARRANCO	-12.0478053999999997	-77.0625787000000031
1253	15	1	5	LIMA	LIMA	BREÑA	-12.0478053999999997	-77.0625787000000031
1254	15	1	6	LIMA	LIMA	CARABAYLLO	-12.0478053999999997	-77.0625787000000031
1255	15	1	7	LIMA	LIMA	CHACLACAYO	-12.0478053999999997	-77.0625787000000031
1256	15	1	8	LIMA	LIMA	CHORRILLOS	-12.0478053999999997	-77.0625787000000031
1257	15	1	9	LIMA	LIMA	CIENEGUILLA	-12.0478053999999997	-77.0625787000000031
1258	15	1	10	LIMA	LIMA	COMAS	-12.0478053999999997	-77.0625787000000031
1259	15	1	11	LIMA	LIMA	EL AGUSTINO	-12.0478053999999997	-77.0625787000000031
1260	15	1	12	LIMA	LIMA	INDEPENDENCIA	-12.0478053999999997	-77.0625787000000031
1261	15	1	13	LIMA	LIMA	JESUS MARIA	-12.0478053999999997	-77.0625787000000031
1262	15	1	14	LIMA	LIMA	LA MOLINA	-12.0478053999999997	-77.0625787000000031
1263	15	1	15	LIMA	LIMA	LA VICTORIA	-12.0478053999999997	-77.0625787000000031
1721	22	4	2	SAN MARTIN	HUALLAGA	ALTO SAPOSOA	-7.26717749999999985	-76.7336892999999947
1722	22	4	3	SAN MARTIN	HUALLAGA	EL ESLABON	-7.26717749999999985	-76.7336892999999947
1723	22	4	4	SAN MARTIN	HUALLAGA	PISCOYACU	-7.26717749999999985	-76.7336892999999947
1724	22	4	5	SAN MARTIN	HUALLAGA	SACANCHE	-7.26717749999999985	-76.7336892999999947
1725	22	4	6	SAN MARTIN	HUALLAGA	TINGO DE SAPOSOA	-7.26717749999999985	-76.7336892999999947
1726	22	5	1	SAN MARTIN	LAMAS	LAMAS	-7.26717749999999985	-76.7336892999999947
1752	22	8	1	SAN MARTIN	RIOJA	RIOJA	-7.26717749999999985	-76.7336892999999947
1753	22	8	2	SAN MARTIN	RIOJA	AWAJUN	-7.26717749999999985	-76.7336892999999947
1754	22	8	3	SAN MARTIN	RIOJA	ELIAS SOPLIN VARGAS	-7.26717749999999985	-76.7336892999999947
1755	22	8	4	SAN MARTIN	RIOJA	NUEVA CAJAMARCA	-7.26717749999999985	-76.7336892999999947
1756	22	8	5	SAN MARTIN	RIOJA	PARDO MIGUEL	-7.26717749999999985	-76.7336892999999947
1757	22	8	6	SAN MARTIN	RIOJA	POSIC	-7.26717749999999985	-76.7336892999999947
1758	22	8	7	SAN MARTIN	RIOJA	SAN FERNANDO	-7.26717749999999985	-76.7336892999999947
1759	22	8	8	SAN MARTIN	RIOJA	YORONGOS	-7.26717749999999985	-76.7336892999999947
1760	22	8	9	SAN MARTIN	RIOJA	YURACYACU	-7.26717749999999985	-76.7336892999999947
1761	22	9	1	SAN MARTIN	SAN MARTIN	TARAPOTO	-7.26717749999999985	-76.7336892999999947
1762	22	9	2	SAN MARTIN	SAN MARTIN	ALBERTO LEVEAU	-7.26717749999999985	-76.7336892999999947
1763	22	9	3	SAN MARTIN	SAN MARTIN	CACATACHI	-7.26717749999999985	-76.7336892999999947
1764	22	9	4	SAN MARTIN	SAN MARTIN	CHAZUTA	-7.26717749999999985	-76.7336892999999947
1765	22	9	5	SAN MARTIN	SAN MARTIN	CHIPURANA	-7.26717749999999985	-76.7336892999999947
1766	22	9	6	SAN MARTIN	SAN MARTIN	EL PORVENIR	-7.26717749999999985	-76.7336892999999947
1767	22	9	7	SAN MARTIN	SAN MARTIN	HUIMBAYOC	-7.26717749999999985	-76.7336892999999947
1768	22	9	8	SAN MARTIN	SAN MARTIN	JUAN GUERRA	-7.26717749999999985	-76.7336892999999947
1769	22	9	9	SAN MARTIN	SAN MARTIN	LA BANDA DE SHILCAYO	-7.26717749999999985	-76.7336892999999947
1770	22	9	10	SAN MARTIN	SAN MARTIN	MORALES	-7.26717749999999985	-76.7336892999999947
1771	22	9	11	SAN MARTIN	SAN MARTIN	PAPAPLAYA	-7.26717749999999985	-76.7336892999999947
1772	22	9	12	SAN MARTIN	SAN MARTIN	SAN ANTONIO	-7.26717749999999985	-76.7336892999999947
1773	22	9	13	SAN MARTIN	SAN MARTIN	SAUCE	-7.26717749999999985	-76.7336892999999947
1774	22	9	14	SAN MARTIN	SAN MARTIN	SHAPAJA	-7.26717749999999985	-76.7336892999999947
1775	22	10	1	SAN MARTIN	TOCACHE	TOCACHE	-7.26717749999999985	-76.7336892999999947
1776	22	10	2	SAN MARTIN	TOCACHE	NUEVO PROGRESO	-7.26717749999999985	-76.7336892999999947
1777	22	10	3	SAN MARTIN	TOCACHE	POLVORA	-7.26717749999999985	-76.7336892999999947
1778	22	10	4	SAN MARTIN	TOCACHE	SHUNTE	-7.26717749999999985	-76.7336892999999947
1779	22	10	5	SAN MARTIN	TOCACHE	UCHIZA	-7.26717749999999985	-76.7336892999999947
1	1	1	1	AMAZONAS	CHACHAPOYAS	CHACHAPOYAS	-4.14284129999999973	-78.1108279000000039
2	1	1	2	AMAZONAS	CHACHAPOYAS	ASUNCION	-4.14284129999999973	-78.1108279000000039
3	1	1	3	AMAZONAS	CHACHAPOYAS	BALSAS	-4.14284129999999973	-78.1108279000000039
4	1	1	4	AMAZONAS	CHACHAPOYAS	CHETO	-4.14284129999999973	-78.1108279000000039
5	1	1	5	AMAZONAS	CHACHAPOYAS	CHILIQUIN	-4.14284129999999973	-78.1108279000000039
6	1	1	6	AMAZONAS	CHACHAPOYAS	CHUQUIBAMBA	-4.14284129999999973	-78.1108279000000039
7	1	1	7	AMAZONAS	CHACHAPOYAS	GRANADA	-4.14284129999999973	-78.1108279000000039
8	1	1	8	AMAZONAS	CHACHAPOYAS	HUANCAS	-4.14284129999999973	-78.1108279000000039
9	1	1	9	AMAZONAS	CHACHAPOYAS	LA JALCA	-4.14284129999999973	-78.1108279000000039
10	1	1	10	AMAZONAS	CHACHAPOYAS	LEIMEBAMBA	-4.14284129999999973	-78.1108279000000039
11	1	1	11	AMAZONAS	CHACHAPOYAS	LEVANTO	-4.14284129999999973	-78.1108279000000039
12	1	1	12	AMAZONAS	CHACHAPOYAS	MAGDALENA	-4.14284129999999973	-78.1108279000000039
13	1	1	13	AMAZONAS	CHACHAPOYAS	MARISCAL CASTILLA	-4.14284129999999973	-78.1108279000000039
14	1	1	14	AMAZONAS	CHACHAPOYAS	MOLINOPAMPA	-4.14284129999999973	-78.1108279000000039
15	1	1	15	AMAZONAS	CHACHAPOYAS	MONTEVIDEO	-4.14284129999999973	-78.1108279000000039
16	1	1	16	AMAZONAS	CHACHAPOYAS	OLLEROS	-4.14284129999999973	-78.1108279000000039
17	1	1	17	AMAZONAS	CHACHAPOYAS	QUINJALCA	-4.14284129999999973	-78.1108279000000039
18	1	1	18	AMAZONAS	CHACHAPOYAS	SAN FRANCISCO DE DAGUAS	-4.14284129999999973	-78.1108279000000039
19	1	1	19	AMAZONAS	CHACHAPOYAS	SAN ISIDRO DE MAINO	-4.14284129999999973	-78.1108279000000039
20	1	1	20	AMAZONAS	CHACHAPOYAS	SOLOCO	-4.14284129999999973	-78.1108279000000039
21	1	1	21	AMAZONAS	CHACHAPOYAS	SONCHE	-4.14284129999999973	-78.1108279000000039
22	1	2	1	AMAZONAS	BAGUA	LA PECA	-4.14284129999999973	-78.1108279000000039
23	1	2	2	AMAZONAS	BAGUA	ARAMANGO	-4.14284129999999973	-78.1108279000000039
24	1	2	3	AMAZONAS	BAGUA	COPALLIN	-4.14284129999999973	-78.1108279000000039
25	1	2	4	AMAZONAS	BAGUA	EL PARCO	-4.14284129999999973	-78.1108279000000039
26	1	2	5	AMAZONAS	BAGUA	IMAZA	-4.14284129999999973	-78.1108279000000039
27	1	2	6	AMAZONAS	BAGUA	BAGUA	-4.14284129999999973	-78.1108279000000039
28	1	3	1	AMAZONAS	BONGARA	JUMBILLA	-4.14284129999999973	-78.1108279000000039
29	1	3	2	AMAZONAS	BONGARA	CHISQUILLA	-4.14284129999999973	-78.1108279000000039
30	1	3	3	AMAZONAS	BONGARA	CHURUJA	-4.14284129999999973	-78.1108279000000039
31	1	3	4	AMAZONAS	BONGARA	COROSHA	-4.14284129999999973	-78.1108279000000039
32	1	3	5	AMAZONAS	BONGARA	CUISPES	-4.14284129999999973	-78.1108279000000039
33	1	3	6	AMAZONAS	BONGARA	FLORIDA	-4.14284129999999973	-78.1108279000000039
34	1	3	7	AMAZONAS	BONGARA	JAZAN	-4.14284129999999973	-78.1108279000000039
35	1	3	8	AMAZONAS	BONGARA	RECTA	-4.14284129999999973	-78.1108279000000039
36	1	3	9	AMAZONAS	BONGARA	SAN CARLOS	-4.14284129999999973	-78.1108279000000039
37	1	3	10	AMAZONAS	BONGARA	SHIPASBAMBA	-4.14284129999999973	-78.1108279000000039
38	1	3	11	AMAZONAS	BONGARA	VALERA	-4.14284129999999973	-78.1108279000000039
39	1	3	12	AMAZONAS	BONGARA	YAMBRASBAMBA	-4.14284129999999973	-78.1108279000000039
40	1	4	1	AMAZONAS	CONDORCANQUI	NIEVA	-4.14284129999999973	-78.1108279000000039
41	1	4	2	AMAZONAS	CONDORCANQUI	EL CENEPA	-4.14284129999999973	-78.1108279000000039
42	1	4	3	AMAZONAS	CONDORCANQUI	RIO SANTIAGO	-4.14284129999999973	-78.1108279000000039
1337	15	7	1	LIMA	HUAROCHIRI	MATUCANA	-12.0478053999999997	-77.0625787000000031
1338	15	7	2	LIMA	HUAROCHIRI	ANTIOQUIA	-12.0478053999999997	-77.0625787000000031
1339	15	7	3	LIMA	HUAROCHIRI	CALLAHUANCA	-12.0478053999999997	-77.0625787000000031
1340	15	7	4	LIMA	HUAROCHIRI	CARAMPOMA	-12.0478053999999997	-77.0625787000000031
1341	15	7	5	LIMA	HUAROCHIRI	CHICLA	-12.0478053999999997	-77.0625787000000031
1342	15	7	6	LIMA	HUAROCHIRI	CUENCA	-12.0478053999999997	-77.0625787000000031
1343	15	7	7	LIMA	HUAROCHIRI	HUACHUPAMPA	-12.0478053999999997	-77.0625787000000031
1344	15	7	8	LIMA	HUAROCHIRI	HUANZA	-12.0478053999999997	-77.0625787000000031
1345	15	7	9	LIMA	HUAROCHIRI	HUAROCHIRI	-12.0478053999999997	-77.0625787000000031
1346	15	7	10	LIMA	HUAROCHIRI	LAHUAYTAMBO	-12.0478053999999997	-77.0625787000000031
1347	15	7	11	LIMA	HUAROCHIRI	LANGA	-12.0478053999999997	-77.0625787000000031
1348	15	7	12	LIMA	HUAROCHIRI	LARAOS	-12.0478053999999997	-77.0625787000000031
1349	15	7	13	LIMA	HUAROCHIRI	MARIATANA	-12.0478053999999997	-77.0625787000000031
1350	15	7	14	LIMA	HUAROCHIRI	RICARDO PALMA	-12.0478053999999997	-77.0625787000000031
1351	15	7	15	LIMA	HUAROCHIRI	SAN ANDRES DE TUPICOCHA	-12.0478053999999997	-77.0625787000000031
1420	16	1	1	LORETO	MAYNAS	IQUITOS	-3.93750549999999988	-75.3412179000000037
43	1	5	1	AMAZONAS	LUYA	LAMUD	-4.14284129999999973	-78.1108279000000039
44	1	5	2	AMAZONAS	LUYA	CAMPORREDONDO	-4.14284129999999973	-78.1108279000000039
45	1	5	3	AMAZONAS	LUYA	COCABAMBA	-4.14284129999999973	-78.1108279000000039
46	1	5	4	AMAZONAS	LUYA	COLCAMAR	-4.14284129999999973	-78.1108279000000039
47	1	5	5	AMAZONAS	LUYA	CONILA	-4.14284129999999973	-78.1108279000000039
48	1	5	6	AMAZONAS	LUYA	INGUILPATA	-4.14284129999999973	-78.1108279000000039
49	1	5	7	AMAZONAS	LUYA	LONGUITA	-4.14284129999999973	-78.1108279000000039
50	1	5	8	AMAZONAS	LUYA	LONYA CHICO	-4.14284129999999973	-78.1108279000000039
51	1	5	9	AMAZONAS	LUYA	LUYA	-4.14284129999999973	-78.1108279000000039
52	1	5	10	AMAZONAS	LUYA	LUYA VIEJO	-4.14284129999999973	-78.1108279000000039
53	1	5	11	AMAZONAS	LUYA	MARIA	-4.14284129999999973	-78.1108279000000039
54	1	5	12	AMAZONAS	LUYA	OCALLI	-4.14284129999999973	-78.1108279000000039
55	1	5	13	AMAZONAS	LUYA	OCUMAL	-4.14284129999999973	-78.1108279000000039
56	1	5	14	AMAZONAS	LUYA	PISUQUIA	-4.14284129999999973	-78.1108279000000039
57	1	5	15	AMAZONAS	LUYA	PROVIDENCIA	-4.14284129999999973	-78.1108279000000039
58	1	5	16	AMAZONAS	LUYA	SAN CRISTOBAL	-4.14284129999999973	-78.1108279000000039
59	1	5	17	AMAZONAS	LUYA	SAN FRANCISCO DEL YESO	-4.14284129999999973	-78.1108279000000039
60	1	5	18	AMAZONAS	LUYA	SAN JERONIMO	-4.14284129999999973	-78.1108279000000039
61	1	5	19	AMAZONAS	LUYA	SAN JUAN DE LOPECANCHA	-4.14284129999999973	-78.1108279000000039
62	1	5	20	AMAZONAS	LUYA	SANTA CATALINA	-4.14284129999999973	-78.1108279000000039
63	1	5	21	AMAZONAS	LUYA	SANTO TOMAS	-4.14284129999999973	-78.1108279000000039
64	1	5	22	AMAZONAS	LUYA	TINGO	-4.14284129999999973	-78.1108279000000039
65	1	5	23	AMAZONAS	LUYA	TRITA	-4.14284129999999973	-78.1108279000000039
66	1	6	1	AMAZONAS	RODRIGUEZ DE MENDOZA	SAN NICOLAS	-4.14284129999999973	-78.1108279000000039
67	1	6	2	AMAZONAS	RODRIGUEZ DE MENDOZA	CHIRIMOTO	-4.14284129999999973	-78.1108279000000039
68	1	6	3	AMAZONAS	RODRIGUEZ DE MENDOZA	COCHAMAL	-4.14284129999999973	-78.1108279000000039
69	1	6	4	AMAZONAS	RODRIGUEZ DE MENDOZA	HUAMBO	-4.14284129999999973	-78.1108279000000039
70	1	6	5	AMAZONAS	RODRIGUEZ DE MENDOZA	LIMABAMBA	-4.14284129999999973	-78.1108279000000039
71	1	6	6	AMAZONAS	RODRIGUEZ DE MENDOZA	LONGAR	-4.14284129999999973	-78.1108279000000039
72	1	6	7	AMAZONAS	RODRIGUEZ DE MENDOZA	MARISCAL BENAVIDES	-4.14284129999999973	-78.1108279000000039
73	1	6	8	AMAZONAS	RODRIGUEZ DE MENDOZA	MILPUC	-4.14284129999999973	-78.1108279000000039
74	1	6	9	AMAZONAS	RODRIGUEZ DE MENDOZA	OMIA	-4.14284129999999973	-78.1108279000000039
75	1	6	10	AMAZONAS	RODRIGUEZ DE MENDOZA	SANTA ROSA	-4.14284129999999973	-78.1108279000000039
76	1	6	11	AMAZONAS	RODRIGUEZ DE MENDOZA	TOTORA	-4.14284129999999973	-78.1108279000000039
77	1	6	12	AMAZONAS	RODRIGUEZ DE MENDOZA	VISTA ALEGRE	-4.14284129999999973	-78.1108279000000039
78	1	7	1	AMAZONAS	UTCUBAMBA	BAGUA GRANDE	-4.14284129999999973	-78.1108279000000039
79	1	7	2	AMAZONAS	UTCUBAMBA	CAJARURO	-4.14284129999999973	-78.1108279000000039
80	1	7	3	AMAZONAS	UTCUBAMBA	CUMBA	-4.14284129999999973	-78.1108279000000039
81	1	7	4	AMAZONAS	UTCUBAMBA	EL MILAGRO	-4.14284129999999973	-78.1108279000000039
82	1	7	5	AMAZONAS	UTCUBAMBA	JAMALCA	-4.14284129999999973	-78.1108279000000039
83	1	7	6	AMAZONAS	UTCUBAMBA	LONYA GRANDE	-4.14284129999999973	-78.1108279000000039
84	1	7	7	AMAZONAS	UTCUBAMBA	YAMON	-4.14284129999999973	-78.1108279000000039
679	7	1	2	CALLAO	CALLAO	BELLAVISTA	-12.0340933000000003	-77.1378476000000006
680	7	1	3	CALLAO	CALLAO	CARMEN DE LA LEGUA REYNOSO	-12.0340933000000003	-77.1378476000000006
681	7	1	4	CALLAO	CALLAO	LA PERLA	-12.0340933000000003	-77.1378476000000006
682	7	1	5	CALLAO	CALLAO	LA PUNTA	-12.0340933000000003	-77.1378476000000006
683	7	1	6	CALLAO	CALLAO	VENTANILLA	-12.0340933000000003	-77.1378476000000006
440	5	1	1	AYACUCHO	HUAMANGA	AYACUCHO	-13.1631107000000007	-74.2246142000000049
441	5	1	2	AYACUCHO	HUAMANGA	ACOCRO	-13.1631107000000007	-74.2246142000000049
442	5	1	3	AYACUCHO	HUAMANGA	ACOS VINCHOS	-13.1631107000000007	-74.2246142000000049
443	5	1	4	AYACUCHO	HUAMANGA	CARMEN ALTO	-13.1631107000000007	-74.2246142000000049
444	5	1	5	AYACUCHO	HUAMANGA	CHIARA	-13.1631107000000007	-74.2246142000000049
445	5	1	6	AYACUCHO	HUAMANGA	OCROS	-13.1631107000000007	-74.2246142000000049
446	5	1	7	AYACUCHO	HUAMANGA	PACAYCASA	-13.1631107000000007	-74.2246142000000049
447	5	1	8	AYACUCHO	HUAMANGA	QUINUA	-13.1631107000000007	-74.2246142000000049
448	5	1	9	AYACUCHO	HUAMANGA	SAN JOSE DE TICLLAS	-13.1631107000000007	-74.2246142000000049
449	5	1	10	AYACUCHO	HUAMANGA	SAN JUAN BAUTISTA	-13.1631107000000007	-74.2246142000000049
450	5	1	11	AYACUCHO	HUAMANGA	SANTIAGO DE PISCHA	-13.1631107000000007	-74.2246142000000049
451	5	1	12	AYACUCHO	HUAMANGA	SOCOS	-13.1631107000000007	-74.2246142000000049
452	5	1	13	AYACUCHO	HUAMANGA	TAMBILLO	-13.1631107000000007	-74.2246142000000049
453	5	1	14	AYACUCHO	HUAMANGA	VINCHOS	-13.1631107000000007	-74.2246142000000049
454	5	1	15	AYACUCHO	HUAMANGA	JESUS NAZARENO	-13.1631107000000007	-74.2246142000000049
455	5	2	1	AYACUCHO	CANGALLO	CANGALLO	-13.1631107000000007	-74.2246142000000049
456	5	2	2	AYACUCHO	CANGALLO	CHUSCHI	-13.1631107000000007	-74.2246142000000049
457	5	2	3	AYACUCHO	CANGALLO	LOS MOROCHUCOS	-13.1631107000000007	-74.2246142000000049
458	5	2	4	AYACUCHO	CANGALLO	MARIA PARADO DE BELLIDO	-13.1631107000000007	-74.2246142000000049
459	5	2	5	AYACUCHO	CANGALLO	PARAS	-13.1631107000000007	-74.2246142000000049
460	5	2	6	AYACUCHO	CANGALLO	TOTOS	-13.1631107000000007	-74.2246142000000049
461	5	3	1	AYACUCHO	HUANCA SANCOS	SANCOS	-13.1631107000000007	-74.2246142000000049
462	5	3	2	AYACUCHO	HUANCA SANCOS	CARAPO	-13.1631107000000007	-74.2246142000000049
463	5	3	3	AYACUCHO	HUANCA SANCOS	SACSAMARCA	-13.1631107000000007	-74.2246142000000049
464	5	3	4	AYACUCHO	HUANCA SANCOS	SANTIAGO DE LUCANAMARCA	-13.1631107000000007	-74.2246142000000049
465	5	4	1	AYACUCHO	HUANTA	HUANTA	-13.1631107000000007	-74.2246142000000049
466	5	4	2	AYACUCHO	HUANTA	AYAHUANCO	-13.1631107000000007	-74.2246142000000049
467	5	4	3	AYACUCHO	HUANTA	HUAMANGUILLA	-13.1631107000000007	-74.2246142000000049
468	5	4	4	AYACUCHO	HUANTA	IGUAIN	-13.1631107000000007	-74.2246142000000049
469	5	4	5	AYACUCHO	HUANTA	LURICOCHA	-13.1631107000000007	-74.2246142000000049
470	5	4	6	AYACUCHO	HUANTA	SANTILLANA	-13.1631107000000007	-74.2246142000000049
471	5	4	7	AYACUCHO	HUANTA	SIVIA	-13.1631107000000007	-74.2246142000000049
472	5	4	8	AYACUCHO	HUANTA	LLOCHEGUA	-13.1631107000000007	-74.2246142000000049
473	5	5	1	AYACUCHO	LA MAR	SAN MIGUEL	-13.1631107000000007	-74.2246142000000049
474	5	5	2	AYACUCHO	LA MAR	ANCO	-13.1631107000000007	-74.2246142000000049
475	5	5	3	AYACUCHO	LA MAR	AYNA	-13.1631107000000007	-74.2246142000000049
476	5	5	4	AYACUCHO	LA MAR	CHILCAS	-13.1631107000000007	-74.2246142000000049
477	5	5	5	AYACUCHO	LA MAR	CHUNGUI	-13.1631107000000007	-74.2246142000000049
478	5	5	6	AYACUCHO	LA MAR	LUIS CARRANZA	-13.1631107000000007	-74.2246142000000049
479	5	5	7	AYACUCHO	LA MAR	SANTA ROSA	-13.1631107000000007	-74.2246142000000049
480	5	5	8	AYACUCHO	LA MAR	TAMBO	-13.1631107000000007	-74.2246142000000049
481	5	6	1	AYACUCHO	LUCANAS	PUQUIO	-13.1631107000000007	-74.2246142000000049
482	5	6	2	AYACUCHO	LUCANAS	AUCARA	-13.1631107000000007	-74.2246142000000049
483	5	6	3	AYACUCHO	LUCANAS	CABANA	-13.1631107000000007	-74.2246142000000049
484	5	6	4	AYACUCHO	LUCANAS	CARMEN SALCEDO	-13.1631107000000007	-74.2246142000000049
485	5	6	5	AYACUCHO	LUCANAS	CHAVIÑA	-13.1631107000000007	-74.2246142000000049
486	5	6	6	AYACUCHO	LUCANAS	CHIPAO	-13.1631107000000007	-74.2246142000000049
487	5	6	7	AYACUCHO	LUCANAS	HUAC-HUAS	-13.1631107000000007	-74.2246142000000049
488	5	6	8	AYACUCHO	LUCANAS	LARAMATE	-13.1631107000000007	-74.2246142000000049
489	5	6	9	AYACUCHO	LUCANAS	LEONCIO PRADO	-13.1631107000000007	-74.2246142000000049
490	5	6	10	AYACUCHO	LUCANAS	LLAUTA	-13.1631107000000007	-74.2246142000000049
491	5	6	11	AYACUCHO	LUCANAS	LUCANAS	-13.1631107000000007	-74.2246142000000049
492	5	6	12	AYACUCHO	LUCANAS	OCAÑA	-13.1631107000000007	-74.2246142000000049
493	5	6	13	AYACUCHO	LUCANAS	OTOCA	-13.1631107000000007	-74.2246142000000049
494	5	6	14	AYACUCHO	LUCANAS	SAISA	-13.1631107000000007	-74.2246142000000049
495	5	6	15	AYACUCHO	LUCANAS	SAN CRISTOBAL	-13.1631107000000007	-74.2246142000000049
496	5	6	16	AYACUCHO	LUCANAS	SAN JUAN	-13.1631107000000007	-74.2246142000000049
497	5	6	17	AYACUCHO	LUCANAS	SAN PEDRO	-13.1631107000000007	-74.2246142000000049
498	5	6	18	AYACUCHO	LUCANAS	SAN PEDRO DE PALCO	-13.1631107000000007	-74.2246142000000049
499	5	6	19	AYACUCHO	LUCANAS	SANCOS	-13.1631107000000007	-74.2246142000000049
500	5	6	20	AYACUCHO	LUCANAS	SANTA ANA DE HUAYCAHUACHO	-13.1631107000000007	-74.2246142000000049
501	5	6	21	AYACUCHO	LUCANAS	SANTA LUCIA	-13.1631107000000007	-74.2246142000000049
1502	19	1	1	PASCO	PASCO	CHAUPIMARCA	-8.0995519999999992	-79.0367389999999972
1503	19	1	2	PASCO	PASCO	HUACHON	-8.0995519999999992	-79.0367389999999972
1504	19	1	3	PASCO	PASCO	HUARIACA	-8.0995519999999992	-79.0367389999999972
1505	19	1	4	PASCO	PASCO	HUAYLLAY	-8.0995519999999992	-79.0367389999999972
1506	19	1	5	PASCO	PASCO	NINACACA	-8.0995519999999992	-79.0367389999999972
1507	19	1	6	PASCO	PASCO	PALLANCHACRA	-8.0995519999999992	-79.0367389999999972
1508	19	1	7	PASCO	PASCO	PAUCARTAMBO	-8.0995519999999992	-79.0367389999999972
1509	19	1	8	PASCO	PASCO	SAN FRANCISCO DE ASIS DE YARUSYACAN	-8.0995519999999992	-79.0367389999999972
1510	19	1	9	PASCO	PASCO	SIMON BOLIVAR	-8.0995519999999992	-79.0367389999999972
1511	19	1	10	PASCO	PASCO	TICLACAYAN	-8.0995519999999992	-79.0367389999999972
1512	19	1	11	PASCO	PASCO	TINYAHUARCO	-8.0995519999999992	-79.0367389999999972
1513	19	1	12	PASCO	PASCO	VICCO	-8.0995519999999992	-79.0367389999999972
1514	19	1	13	PASCO	PASCO	YANACANCHA	-8.0995519999999992	-79.0367389999999972
1515	19	2	1	PASCO	DANIEL ALCIDES CARRION	YANAHUANCA	-8.0995519999999992	-79.0367389999999972
1516	19	2	2	PASCO	DANIEL ALCIDES CARRION	CHACAYAN	-8.0995519999999992	-79.0367389999999972
1517	19	2	3	PASCO	DANIEL ALCIDES CARRION	GOYLLARISQUIZGA	-8.0995519999999992	-79.0367389999999972
1518	19	2	4	PASCO	DANIEL ALCIDES CARRION	PAUCAR	-8.0995519999999992	-79.0367389999999972
1519	19	2	5	PASCO	DANIEL ALCIDES CARRION	SAN PEDRO DE PILLAO	-8.0995519999999992	-79.0367389999999972
1520	19	2	6	PASCO	DANIEL ALCIDES CARRION	SANTA ANA DE TUSI	-8.0995519999999992	-79.0367389999999972
1521	19	2	7	PASCO	DANIEL ALCIDES CARRION	TAPUC	-8.0995519999999992	-79.0367389999999972
1522	19	2	8	PASCO	DANIEL ALCIDES CARRION	VILCABAMBA	-8.0995519999999992	-79.0367389999999972
1523	19	3	1	PASCO	OXAPAMPA	OXAPAMPA	-8.0995519999999992	-79.0367389999999972
1524	19	3	2	PASCO	OXAPAMPA	CHONTABAMBA	-8.0995519999999992	-79.0367389999999972
1525	19	3	3	PASCO	OXAPAMPA	HUANCABAMBA	-8.0995519999999992	-79.0367389999999972
1584	20	7	3	PIURA	TALARA	LA BREA	-5.19994729999999983	-80.633630299999993
1482	18	1	1	MOQUEGUA	MARISCAL NIETO	MOQUEGUA	-17.1938440999999997	-70.931410200000002
1483	18	1	2	MOQUEGUA	MARISCAL NIETO	CARUMAS	-17.1938440999999997	-70.931410200000002
1484	18	1	3	MOQUEGUA	MARISCAL NIETO	CUCHUMBAYA	-17.1938440999999997	-70.931410200000002
1485	18	1	4	MOQUEGUA	MARISCAL NIETO	SAMEGUA	-17.1938440999999997	-70.931410200000002
1486	18	1	5	MOQUEGUA	MARISCAL NIETO	SAN CRISTOBAL	-17.1938440999999997	-70.931410200000002
1487	18	1	6	MOQUEGUA	MARISCAL NIETO	TORATA	-17.1938440999999997	-70.931410200000002
1488	18	2	1	MOQUEGUA	GENERAL SANCHEZ CERRO	OMATE	-17.1938440999999997	-70.931410200000002
1489	18	2	2	MOQUEGUA	GENERAL SANCHEZ CERRO	CHOJATA	-17.1938440999999997	-70.931410200000002
1490	18	2	3	MOQUEGUA	GENERAL SANCHEZ CERRO	COALAQUE	-17.1938440999999997	-70.931410200000002
1491	18	2	4	MOQUEGUA	GENERAL SANCHEZ CERRO	ICHUÑA	-17.1938440999999997	-70.931410200000002
1499	18	3	1	MOQUEGUA	ILO	ILO	-17.1938440999999997	-70.931410200000002
1436	16	2	6	LORETO	ALTO AMAZONAS	LAGUNAS	-3.93750549999999988	-75.3412179000000037
1437	16	2	10	LORETO	ALTO AMAZONAS	SANTA CRUZ	-3.93750549999999988	-75.3412179000000037
1438	16	2	11	LORETO	ALTO AMAZONAS	TENIENTE CESAR LOPEZ ROJAS	-3.93750549999999988	-75.3412179000000037
1439	16	3	1	LORETO	LORETO	NAUTA	-3.93750549999999988	-75.3412179000000037
1440	16	3	2	LORETO	LORETO	PARINARI	-3.93750549999999988	-75.3412179000000037
1441	16	3	3	LORETO	LORETO	TIGRE	-3.93750549999999988	-75.3412179000000037
1442	16	3	4	LORETO	LORETO	TROMPETEROS	-3.93750549999999988	-75.3412179000000037
1443	16	3	5	LORETO	LORETO	URARINAS	-3.93750549999999988	-75.3412179000000037
1444	16	4	1	LORETO	MARISCAL RAMON CASTILLA	RAMON CASTILLA	-3.93750549999999988	-75.3412179000000037
1445	16	4	2	LORETO	MARISCAL RAMON CASTILLA	PEBAS	-3.93750549999999988	-75.3412179000000037
1446	16	4	3	LORETO	MARISCAL RAMON CASTILLA	YAVARI	-3.93750549999999988	-75.3412179000000037
1447	16	4	4	LORETO	MARISCAL RAMON CASTILLA	SAN PABLO	-3.93750549999999988	-75.3412179000000037
1448	16	5	1	LORETO	REQUENA	REQUENA	-3.93750549999999988	-75.3412179000000037
1449	16	5	2	LORETO	REQUENA	ALTO TAPICHE	-3.93750549999999988	-75.3412179000000037
1450	16	5	3	LORETO	REQUENA	CAPELO	-3.93750549999999988	-75.3412179000000037
1451	16	5	4	LORETO	REQUENA	EMILIO SAN MARTIN	-3.93750549999999988	-75.3412179000000037
1452	16	5	5	LORETO	REQUENA	MAQUIA	-3.93750549999999988	-75.3412179000000037
1453	16	5	6	LORETO	REQUENA	PUINAHUA	-3.93750549999999988	-75.3412179000000037
1454	16	5	7	LORETO	REQUENA	SAQUENA	-3.93750549999999988	-75.3412179000000037
1455	16	5	8	LORETO	REQUENA	SOPLIN	-3.93750549999999988	-75.3412179000000037
502	5	7	1	AYACUCHO	PARINACOCHAS	CORACORA	-13.1631107000000007	-74.2246142000000049
1526	19	3	4	PASCO	OXAPAMPA	PALCAZU	-8.0995519999999992	-79.0367389999999972
1527	19	3	5	PASCO	OXAPAMPA	POZUZO	-8.0995519999999992	-79.0367389999999972
1528	19	3	6	PASCO	OXAPAMPA	PUERTO BERMUDEZ	-8.0995519999999992	-79.0367389999999972
1529	19	3	7	PASCO	OXAPAMPA	VILLA RICA	-8.0995519999999992	-79.0367389999999972
630	6	8	7	CAJAMARCA	JAEN	POMAHUACA	-7.16246730000000031	-78.5103021999999982
631	6	8	8	CAJAMARCA	JAEN	PUCARA	-7.16246730000000031	-78.5103021999999982
632	6	8	9	CAJAMARCA	JAEN	SALLIQUE	-7.16246730000000031	-78.5103021999999982
633	6	8	10	CAJAMARCA	JAEN	SAN FELIPE	-7.16246730000000031	-78.5103021999999982
634	6	8	11	CAJAMARCA	JAEN	SAN JOSE DEL ALTO	-7.16246730000000031	-78.5103021999999982
635	6	8	12	CAJAMARCA	JAEN	SANTA ROSA	-7.16246730000000031	-78.5103021999999982
636	6	9	1	CAJAMARCA	SAN IGNACIO	SAN IGNACIO	-7.16246730000000031	-78.5103021999999982
637	6	9	2	CAJAMARCA	SAN IGNACIO	CHIRINOS	-7.16246730000000031	-78.5103021999999982
638	6	9	3	CAJAMARCA	SAN IGNACIO	HUARANGO	-7.16246730000000031	-78.5103021999999982
639	6	9	4	CAJAMARCA	SAN IGNACIO	LA COIPA	-7.16246730000000031	-78.5103021999999982
640	6	9	5	CAJAMARCA	SAN IGNACIO	NAMBALLE	-7.16246730000000031	-78.5103021999999982
641	6	9	6	CAJAMARCA	SAN IGNACIO	SAN JOSE DE LOURDES	-7.16246730000000031	-78.5103021999999982
642	6	9	7	CAJAMARCA	SAN IGNACIO	TABACONAS	-7.16246730000000031	-78.5103021999999982
643	6	10	1	CAJAMARCA	SAN MARCOS	PEDRO GALVEZ	-7.16246730000000031	-78.5103021999999982
644	6	10	2	CAJAMARCA	SAN MARCOS	CHANCAY	-7.16246730000000031	-78.5103021999999982
645	6	10	3	CAJAMARCA	SAN MARCOS	EDUARDO VILLANUEVA	-7.16246730000000031	-78.5103021999999982
646	6	10	4	CAJAMARCA	SAN MARCOS	GREGORIO PITA	-7.16246730000000031	-78.5103021999999982
647	6	10	5	CAJAMARCA	SAN MARCOS	ICHOCAN	-7.16246730000000031	-78.5103021999999982
648	6	10	6	CAJAMARCA	SAN MARCOS	JOSE MANUEL QUIROZ	-7.16246730000000031	-78.5103021999999982
649	6	10	7	CAJAMARCA	SAN MARCOS	JOSE SABOGAL	-7.16246730000000031	-78.5103021999999982
650	6	11	1	CAJAMARCA	SAN MIGUEL	SAN MIGUEL	-7.16246730000000031	-78.5103021999999982
651	6	11	2	CAJAMARCA	SAN MIGUEL	BOLIVAR	-7.16246730000000031	-78.5103021999999982
652	6	11	3	CAJAMARCA	SAN MIGUEL	CALQUIS	-7.16246730000000031	-78.5103021999999982
653	6	11	4	CAJAMARCA	SAN MIGUEL	CATILLUC	-7.16246730000000031	-78.5103021999999982
654	6	11	5	CAJAMARCA	SAN MIGUEL	EL PRADO	-7.16246730000000031	-78.5103021999999982
655	6	11	6	CAJAMARCA	SAN MIGUEL	LA FLORIDA	-7.16246730000000031	-78.5103021999999982
656	6	11	7	CAJAMARCA	SAN MIGUEL	LLAPA	-7.16246730000000031	-78.5103021999999982
657	6	11	8	CAJAMARCA	SAN MIGUEL	NANCHOC	-7.16246730000000031	-78.5103021999999982
658	6	11	9	CAJAMARCA	SAN MIGUEL	NIEPOS	-7.16246730000000031	-78.5103021999999982
659	6	11	10	CAJAMARCA	SAN MIGUEL	SAN GREGORIO	-7.16246730000000031	-78.5103021999999982
660	6	11	11	CAJAMARCA	SAN MIGUEL	SAN SILVESTRE DE COCHAN	-7.16246730000000031	-78.5103021999999982
661	6	11	12	CAJAMARCA	SAN MIGUEL	TONGOD	-7.16246730000000031	-78.5103021999999982
662	6	11	13	CAJAMARCA	SAN MIGUEL	UNION AGUA BLANCA	-7.16246730000000031	-78.5103021999999982
663	6	12	1	CAJAMARCA	SAN PABLO	SAN PABLO	-7.16246730000000031	-78.5103021999999982
664	6	12	2	CAJAMARCA	SAN PABLO	SAN BERNARDINO	-7.16246730000000031	-78.5103021999999982
665	6	12	3	CAJAMARCA	SAN PABLO	SAN LUIS	-7.16246730000000031	-78.5103021999999982
666	6	12	4	CAJAMARCA	SAN PABLO	TUMBADEN	-7.16246730000000031	-78.5103021999999982
667	6	13	1	CAJAMARCA	SANTA CRUZ	SANTA CRUZ	-7.16246730000000031	-78.5103021999999982
668	6	13	2	CAJAMARCA	SANTA CRUZ	ANDABAMBA	-7.16246730000000031	-78.5103021999999982
669	6	13	3	CAJAMARCA	SANTA CRUZ	CATACHE	-7.16246730000000031	-78.5103021999999982
670	6	13	4	CAJAMARCA	SANTA CRUZ	CHANCAYBAÑOS	-7.16246730000000031	-78.5103021999999982
671	6	13	5	CAJAMARCA	SANTA CRUZ	LA ESPERANZA	-7.16246730000000031	-78.5103021999999982
672	6	13	6	CAJAMARCA	SANTA CRUZ	NINABAMBA	-7.16246730000000031	-78.5103021999999982
673	6	13	7	CAJAMARCA	SANTA CRUZ	PULAN	-7.16246730000000031	-78.5103021999999982
674	6	13	8	CAJAMARCA	SANTA CRUZ	SAUCEPAMPA	-7.16246730000000031	-78.5103021999999982
675	6	13	9	CAJAMARCA	SANTA CRUZ	SEXI	-7.16246730000000031	-78.5103021999999982
676	6	13	10	CAJAMARCA	SANTA CRUZ	UTICYACU	-7.16246730000000031	-78.5103021999999982
677	6	13	11	CAJAMARCA	SANTA CRUZ	YAUYUCAN	-7.16246730000000031	-78.5103021999999982
700	8	3	2	CUSCO	ANTA	ANCAHUASI	-13.5247492999999999	-71.9725027000000068
701	8	3	3	CUSCO	ANTA	CACHIMAYO	-13.5247492999999999	-71.9725027000000068
702	8	3	4	CUSCO	ANTA	CHINCHAYPUJIO	-13.5247492999999999	-71.9725027000000068
703	8	3	5	CUSCO	ANTA	HUAROCONDO	-13.5247492999999999	-71.9725027000000068
704	8	3	6	CUSCO	ANTA	LIMATAMBO	-13.5247492999999999	-71.9725027000000068
705	8	3	7	CUSCO	ANTA	MOLLEPATA	-13.5247492999999999	-71.9725027000000068
706	8	3	8	CUSCO	ANTA	PUCYURA	-13.5247492999999999	-71.9725027000000068
707	8	3	9	CUSCO	ANTA	ZURITE	-13.5247492999999999	-71.9725027000000068
708	8	4	1	CUSCO	CALCA	CALCA	-13.5247492999999999	-71.9725027000000068
709	8	4	2	CUSCO	CALCA	COYA	-13.5247492999999999	-71.9725027000000068
710	8	4	3	CUSCO	CALCA	LAMAY	-13.5247492999999999	-71.9725027000000068
1594	21	1	1	PUNO	PUNO	PUNO	-8.0995519999999992	-79.0367389999999972
1595	21	1	2	PUNO	PUNO	ACORA	-8.0995519999999992	-79.0367389999999972
1596	21	1	3	PUNO	PUNO	AMANTANI	-8.0995519999999992	-79.0367389999999972
1597	21	1	4	PUNO	PUNO	ATUNCOLLA	-8.0995519999999992	-79.0367389999999972
1598	21	1	5	PUNO	PUNO	CAPACHICA	-8.0995519999999992	-79.0367389999999972
1599	21	1	6	PUNO	PUNO	CHUCUITO	-8.0995519999999992	-79.0367389999999972
1600	21	1	7	PUNO	PUNO	COATA	-8.0995519999999992	-79.0367389999999972
1601	21	1	8	PUNO	PUNO	HUATA	-8.0995519999999992	-79.0367389999999972
1602	21	1	9	PUNO	PUNO	MAÑAZO	-8.0995519999999992	-79.0367389999999972
1603	21	1	10	PUNO	PUNO	PAUCARCOLLA	-8.0995519999999992	-79.0367389999999972
1604	21	1	11	PUNO	PUNO	PICHACANI	-8.0995519999999992	-79.0367389999999972
1605	21	1	12	PUNO	PUNO	PLATERIA	-8.0995519999999992	-79.0367389999999972
1606	21	1	13	PUNO	PUNO	SAN ANTONIO	-8.0995519999999992	-79.0367389999999972
1607	21	1	14	PUNO	PUNO	TIQUILLACA	-8.0995519999999992	-79.0367389999999972
1608	21	1	15	PUNO	PUNO	VILQUE	-8.0995519999999992	-79.0367389999999972
1609	21	2	1	PUNO	AZANGARO	AZANGARO	-8.0995519999999992	-79.0367389999999972
1610	21	2	2	PUNO	AZANGARO	ACHAYA	-8.0995519999999992	-79.0367389999999972
1611	21	2	3	PUNO	AZANGARO	ARAPA	-8.0995519999999992	-79.0367389999999972
1612	21	2	4	PUNO	AZANGARO	ASILLO	-8.0995519999999992	-79.0367389999999972
1613	21	2	5	PUNO	AZANGARO	CAMINACA	-8.0995519999999992	-79.0367389999999972
1614	21	2	6	PUNO	AZANGARO	CHUPA	-8.0995519999999992	-79.0367389999999972
1615	21	2	7	PUNO	AZANGARO	JOSE DOMINGO CHOQUEHUANCA	-8.0995519999999992	-79.0367389999999972
1616	21	2	8	PUNO	AZANGARO	MUÑANI	-8.0995519999999992	-79.0367389999999972
1617	21	2	9	PUNO	AZANGARO	POTONI	-8.0995519999999992	-79.0367389999999972
1618	21	2	10	PUNO	AZANGARO	SAMAN	-8.0995519999999992	-79.0367389999999972
1619	21	2	11	PUNO	AZANGARO	SAN ANTON	-8.0995519999999992	-79.0367389999999972
1620	21	2	12	PUNO	AZANGARO	SAN JOSE	-8.0995519999999992	-79.0367389999999972
1621	21	2	13	PUNO	AZANGARO	SAN JUAN DE SALINAS	-8.0995519999999992	-79.0367389999999972
1622	21	2	14	PUNO	AZANGARO	SANTIAGO DE PUPUJA	-8.0995519999999992	-79.0367389999999972
1623	21	2	15	PUNO	AZANGARO	TIRAPATA	-8.0995519999999992	-79.0367389999999972
1624	21	3	1	PUNO	CARABAYA	MACUSANI	-8.0995519999999992	-79.0367389999999972
1625	21	3	2	PUNO	CARABAYA	AJOYANI	-8.0995519999999992	-79.0367389999999972
1626	21	3	3	PUNO	CARABAYA	AYAPATA	-8.0995519999999992	-79.0367389999999972
1627	21	3	4	PUNO	CARABAYA	COASA	-8.0995519999999992	-79.0367389999999972
1628	21	3	5	PUNO	CARABAYA	CORANI	-8.0995519999999992	-79.0367389999999972
1629	21	3	6	PUNO	CARABAYA	CRUCERO	-8.0995519999999992	-79.0367389999999972
1630	21	3	7	PUNO	CARABAYA	ITUATA	-8.0995519999999992	-79.0367389999999972
1631	21	3	8	PUNO	CARABAYA	OLLACHEA	-8.0995519999999992	-79.0367389999999972
1632	21	3	9	PUNO	CARABAYA	SAN GABAN	-8.0995519999999992	-79.0367389999999972
1633	21	3	10	PUNO	CARABAYA	USICAYOS	-8.0995519999999992	-79.0367389999999972
1634	21	4	1	PUNO	CHUCUITO	JULI	-8.0995519999999992	-79.0367389999999972
1635	21	4	2	PUNO	CHUCUITO	DESAGUADERO	-8.0995519999999992	-79.0367389999999972
1636	21	4	3	PUNO	CHUCUITO	HUACULLANI	-8.0995519999999992	-79.0367389999999972
1637	21	4	4	PUNO	CHUCUITO	KELLUYO	-8.0995519999999992	-79.0367389999999972
1638	21	4	5	PUNO	CHUCUITO	PISACOMA	-8.0995519999999992	-79.0367389999999972
1639	21	4	6	PUNO	CHUCUITO	POMATA	-8.0995519999999992	-79.0367389999999972
1640	21	4	7	PUNO	CHUCUITO	ZEPITA	-8.0995519999999992	-79.0367389999999972
1641	21	5	1	PUNO	EL COLLAO	ILAVE	-8.0995519999999992	-79.0367389999999972
1642	21	5	2	PUNO	EL COLLAO	CAPAZO	-8.0995519999999992	-79.0367389999999972
1643	21	5	3	PUNO	EL COLLAO	PILCUYO	-8.0995519999999992	-79.0367389999999972
1644	21	5	4	PUNO	EL COLLAO	SANTA ROSA	-8.0995519999999992	-79.0367389999999972
1645	21	5	5	PUNO	EL COLLAO	CONDURIRI	-8.0995519999999992	-79.0367389999999972
1646	21	6	1	PUNO	HUANCANE	HUANCANE	-8.0995519999999992	-79.0367389999999972
1647	21	6	2	PUNO	HUANCANE	COJATA	-8.0995519999999992	-79.0367389999999972
1648	21	6	3	PUNO	HUANCANE	HUATASANI	-8.0995519999999992	-79.0367389999999972
1649	21	6	4	PUNO	HUANCANE	INCHUPALLA	-8.0995519999999992	-79.0367389999999972
1650	21	6	5	PUNO	HUANCANE	PUSI	-8.0995519999999992	-79.0367389999999972
1651	21	6	6	PUNO	HUANCANE	ROSASPATA	-8.0995519999999992	-79.0367389999999972
1652	21	6	7	PUNO	HUANCANE	TARACO	-8.0995519999999992	-79.0367389999999972
1653	21	6	8	PUNO	HUANCANE	VILQUE CHICO	-8.0995519999999992	-79.0367389999999972
1654	21	7	1	PUNO	LAMPA	LAMPA	-8.0995519999999992	-79.0367389999999972
1655	21	7	2	PUNO	LAMPA	CABANILLA	-8.0995519999999992	-79.0367389999999972
1656	21	7	3	PUNO	LAMPA	CALAPUJA	-8.0995519999999992	-79.0367389999999972
1657	21	7	4	PUNO	LAMPA	NICASIO	-8.0995519999999992	-79.0367389999999972
1658	21	7	5	PUNO	LAMPA	OCUVIRI	-8.0995519999999992	-79.0367389999999972
1659	21	7	6	PUNO	LAMPA	PALCA	-8.0995519999999992	-79.0367389999999972
1660	21	7	7	PUNO	LAMPA	PARATIA	-8.0995519999999992	-79.0367389999999972
1661	21	7	8	PUNO	LAMPA	PUCARA	-8.0995519999999992	-79.0367389999999972
1662	21	7	9	PUNO	LAMPA	SANTA LUCIA	-8.0995519999999992	-79.0367389999999972
1663	21	7	10	PUNO	LAMPA	VILAVILA	-8.0995519999999992	-79.0367389999999972
1664	21	8	1	PUNO	MELGAR	AYAVIRI	-8.0995519999999992	-79.0367389999999972
1665	21	8	2	PUNO	MELGAR	ANTAUTA	-8.0995519999999992	-79.0367389999999972
1666	21	8	3	PUNO	MELGAR	CUPI	-8.0995519999999992	-79.0367389999999972
1667	21	8	4	PUNO	MELGAR	LLALLI	-8.0995519999999992	-79.0367389999999972
1668	21	8	5	PUNO	MELGAR	MACARI	-8.0995519999999992	-79.0367389999999972
1669	21	8	6	PUNO	MELGAR	NUÑOA	-8.0995519999999992	-79.0367389999999972
1670	21	8	7	PUNO	MELGAR	ORURILLO	-8.0995519999999992	-79.0367389999999972
1671	21	8	8	PUNO	MELGAR	SANTA ROSA	-8.0995519999999992	-79.0367389999999972
1672	21	8	9	PUNO	MELGAR	UMACHIRI	-8.0995519999999992	-79.0367389999999972
1673	21	9	1	PUNO	MOHO	MOHO	-8.0995519999999992	-79.0367389999999972
1674	21	9	2	PUNO	MOHO	CONIMA	-8.0995519999999992	-79.0367389999999972
1675	21	9	3	PUNO	MOHO	HUAYRAPATA	-8.0995519999999992	-79.0367389999999972
1676	21	9	4	PUNO	MOHO	TILALI	-8.0995519999999992	-79.0367389999999972
1677	21	10	1	PUNO	SAN ANTONIO DE PUTINA	PUTINA	-8.0995519999999992	-79.0367389999999972
1678	21	10	2	PUNO	SAN ANTONIO DE PUTINA	ANANEA	-8.0995519999999992	-79.0367389999999972
1679	21	10	3	PUNO	SAN ANTONIO DE PUTINA	PEDRO VILCA APAZA	-8.0995519999999992	-79.0367389999999972
1680	21	10	4	PUNO	SAN ANTONIO DE PUTINA	QUILCAPUNCU	-8.0995519999999992	-79.0367389999999972
1681	21	10	5	PUNO	SAN ANTONIO DE PUTINA	SINA	-8.0995519999999992	-79.0367389999999972
1682	21	11	1	PUNO	SAN ROMAN	JULIACA	-8.0995519999999992	-79.0367389999999972
1683	21	11	2	PUNO	SAN ROMAN	CABANA	-8.0995519999999992	-79.0367389999999972
1684	21	11	3	PUNO	SAN ROMAN	CABANILLAS	-8.0995519999999992	-79.0367389999999972
1685	21	11	4	PUNO	SAN ROMAN	CARACOTO	-8.0995519999999992	-79.0367389999999972
1686	21	12	1	PUNO	SANDIA	SANDIA	-8.0995519999999992	-79.0367389999999972
1687	21	12	2	PUNO	SANDIA	CUYOCUYO	-8.0995519999999992	-79.0367389999999972
1688	21	12	3	PUNO	SANDIA	LIMBANI	-8.0995519999999992	-79.0367389999999972
1689	21	12	4	PUNO	SANDIA	PATAMBUCO	-8.0995519999999992	-79.0367389999999972
1690	21	12	5	PUNO	SANDIA	PHARA	-8.0995519999999992	-79.0367389999999972
1691	21	12	6	PUNO	SANDIA	QUIACA	-8.0995519999999992	-79.0367389999999972
1692	21	12	7	PUNO	SANDIA	SAN JUAN DEL ORO	-8.0995519999999992	-79.0367389999999972
1693	21	12	8	PUNO	SANDIA	YANAHUAYA	-8.0995519999999992	-79.0367389999999972
1694	21	12	9	PUNO	SANDIA	ALTO INAMBARI	-8.0995519999999992	-79.0367389999999972
1695	21	12	10	PUNO	SANDIA	SAN PEDRO DE PUTINA PUNCO	-8.0995519999999992	-79.0367389999999972
1696	21	13	1	PUNO	YUNGUYO	YUNGUYO	-8.0995519999999992	-79.0367389999999972
1697	21	13	2	PUNO	YUNGUYO	ANAPIA	-8.0995519999999992	-79.0367389999999972
1698	21	13	3	PUNO	YUNGUYO	COPANI	-8.0995519999999992	-79.0367389999999972
1699	21	13	4	PUNO	YUNGUYO	CUTURAPI	-8.0995519999999992	-79.0367389999999972
1700	21	13	5	PUNO	YUNGUYO	OLLARAYA	-8.0995519999999992	-79.0367389999999972
1701	21	13	6	PUNO	YUNGUYO	TINICACHI	-8.0995519999999992	-79.0367389999999972
1702	21	13	7	PUNO	YUNGUYO	UNICACHI	-8.0995519999999992	-79.0367389999999972
1727	22	5	2	SAN MARTIN	LAMAS	ALONSO DE ALVARADO	-7.26717749999999985	-76.7336892999999947
1728	22	5	3	SAN MARTIN	LAMAS	BARRANQUITA	-7.26717749999999985	-76.7336892999999947
1729	22	5	4	SAN MARTIN	LAMAS	CAYNARACHI	-7.26717749999999985	-76.7336892999999947
1730	22	5	5	SAN MARTIN	LAMAS	CUÑUMBUQUI	-7.26717749999999985	-76.7336892999999947
1731	22	5	6	SAN MARTIN	LAMAS	PINTO RECODO	-7.26717749999999985	-76.7336892999999947
1732	22	5	7	SAN MARTIN	LAMAS	RUMISAPA	-7.26717749999999985	-76.7336892999999947
1733	22	5	8	SAN MARTIN	LAMAS	SAN ROQUE DE CUMBAZA	-7.26717749999999985	-76.7336892999999947
1734	22	5	9	SAN MARTIN	LAMAS	SHANAO	-7.26717749999999985	-76.7336892999999947
1735	22	5	10	SAN MARTIN	LAMAS	TABALOSOS	-7.26717749999999985	-76.7336892999999947
1736	22	5	11	SAN MARTIN	LAMAS	ZAPATERO	-7.26717749999999985	-76.7336892999999947
1737	22	6	1	SAN MARTIN	MARISCAL CACERES	JUANJUI	-7.26717749999999985	-76.7336892999999947
1738	22	6	2	SAN MARTIN	MARISCAL CACERES	CAMPANILLA	-7.26717749999999985	-76.7336892999999947
1739	22	6	3	SAN MARTIN	MARISCAL CACERES	HUICUNGO	-7.26717749999999985	-76.7336892999999947
1740	22	6	4	SAN MARTIN	MARISCAL CACERES	PACHIZA	-7.26717749999999985	-76.7336892999999947
1741	22	6	5	SAN MARTIN	MARISCAL CACERES	PAJARILLO	-7.26717749999999985	-76.7336892999999947
1742	22	7	1	SAN MARTIN	PICOTA	PICOTA	-7.26717749999999985	-76.7336892999999947
1743	22	7	2	SAN MARTIN	PICOTA	BUENOS AIRES	-7.26717749999999985	-76.7336892999999947
1744	22	7	3	SAN MARTIN	PICOTA	CASPISAPA	-7.26717749999999985	-76.7336892999999947
1745	22	7	4	SAN MARTIN	PICOTA	PILLUANA	-7.26717749999999985	-76.7336892999999947
1746	22	7	5	SAN MARTIN	PICOTA	PUCACACA	-7.26717749999999985	-76.7336892999999947
1747	22	7	6	SAN MARTIN	PICOTA	SAN CRISTOBAL	-7.26717749999999985	-76.7336892999999947
1748	22	7	7	SAN MARTIN	PICOTA	SAN HILARION	-7.26717749999999985	-76.7336892999999947
1749	22	7	8	SAN MARTIN	PICOTA	SHAMBOYACU	-7.26717749999999985	-76.7336892999999947
1750	22	7	9	SAN MARTIN	PICOTA	TINGO DE PONASA	-7.26717749999999985	-76.7336892999999947
1751	22	7	10	SAN MARTIN	PICOTA	TRES UNIDOS	-7.26717749999999985	-76.7336892999999947
503	5	7	2	AYACUCHO	PARINACOCHAS	CHUMPI	-13.1631107000000007	-74.2246142000000049
504	5	7	3	AYACUCHO	PARINACOCHAS	CORONEL CASTAÑEDA	-13.1631107000000007	-74.2246142000000049
1807	24	1	1	TUMBES	TUMBES	TUMBES	-3.56646980000000013	-80.4491809999999958
1808	24	1	2	TUMBES	TUMBES	CORRALES	-3.56646980000000013	-80.4491809999999958
1809	24	1	3	TUMBES	TUMBES	LA CRUZ	-3.56646980000000013	-80.4491809999999958
1810	24	1	4	TUMBES	TUMBES	PAMPAS DE HOSPITAL	-3.56646980000000013	-80.4491809999999958
1811	24	1	5	TUMBES	TUMBES	SAN JACINTO	-3.56646980000000013	-80.4491809999999958
1812	24	1	6	TUMBES	TUMBES	SAN JUAN DE LA VIRGEN	-3.56646980000000013	-80.4491809999999958
1813	24	2	1	TUMBES	CONTRALMIRANTE VILLAR	ZORRITOS	-3.56646980000000013	-80.4491809999999958
1814	24	2	2	TUMBES	CONTRALMIRANTE VILLAR	CASITAS	-3.56646980000000013	-80.4491809999999958
1815	24	2	3	TUMBES	CONTRALMIRANTE VILLAR	CANOAS DE PUNTA SAL	-3.56646980000000013	-80.4491809999999958
1816	24	3	1	TUMBES	ZARUMILLA	ZARUMILLA	-3.56646980000000013	-80.4491809999999958
1817	24	3	2	TUMBES	ZARUMILLA	AGUAS VERDES	-3.56646980000000013	-80.4491809999999958
1818	24	3	3	TUMBES	ZARUMILLA	MATAPALO	-3.56646980000000013	-80.4491809999999958
1819	24	3	4	TUMBES	ZARUMILLA	PAPAYAL	-3.56646980000000013	-80.4491809999999958
1820	25	1	1	UCAYALI	CORONEL PORTILLO	CALLERIA	-10.5166257999999999	-73.0877490000000023
505	5	7	4	AYACUCHO	PARINACOCHAS	PACAPAUSA	-13.1631107000000007	-74.2246142000000049
506	5	7	5	AYACUCHO	PARINACOCHAS	PULLO	-13.1631107000000007	-74.2246142000000049
507	5	7	6	AYACUCHO	PARINACOCHAS	PUYUSCA	-13.1631107000000007	-74.2246142000000049
508	5	7	7	AYACUCHO	PARINACOCHAS	SAN FRANCISCO DE RAVACAYCO	-13.1631107000000007	-74.2246142000000049
509	5	7	8	AYACUCHO	PARINACOCHAS	UPAHUACHO	-13.1631107000000007	-74.2246142000000049
510	5	8	1	AYACUCHO	PAUCAR DEL SARA SARA	PAUSA	-13.1631107000000007	-74.2246142000000049
511	5	8	2	AYACUCHO	PAUCAR DEL SARA SARA	COLTA	-13.1631107000000007	-74.2246142000000049
512	5	8	3	AYACUCHO	PAUCAR DEL SARA SARA	CORCULLA	-13.1631107000000007	-74.2246142000000049
513	5	8	4	AYACUCHO	PAUCAR DEL SARA SARA	LAMPA	-13.1631107000000007	-74.2246142000000049
514	5	8	5	AYACUCHO	PAUCAR DEL SARA SARA	MARCABAMBA	-13.1631107000000007	-74.2246142000000049
515	5	8	6	AYACUCHO	PAUCAR DEL SARA SARA	OYOLO	-13.1631107000000007	-74.2246142000000049
516	5	8	7	AYACUCHO	PAUCAR DEL SARA SARA	PARARCA	-13.1631107000000007	-74.2246142000000049
517	5	8	8	AYACUCHO	PAUCAR DEL SARA SARA	SAN JAVIER DE ALPABAMBA	-13.1631107000000007	-74.2246142000000049
518	5	8	9	AYACUCHO	PAUCAR DEL SARA SARA	SAN JOSE DE USHUA	-13.1631107000000007	-74.2246142000000049
519	5	8	10	AYACUCHO	PAUCAR DEL SARA SARA	SARA SARA	-13.1631107000000007	-74.2246142000000049
520	5	9	1	AYACUCHO	SUCRE	QUEROBAMBA	-13.1631107000000007	-74.2246142000000049
521	5	9	2	AYACUCHO	SUCRE	BELEN	-13.1631107000000007	-74.2246142000000049
522	5	9	3	AYACUCHO	SUCRE	CHALCOS	-13.1631107000000007	-74.2246142000000049
523	5	9	4	AYACUCHO	SUCRE	CHILCAYOC	-13.1631107000000007	-74.2246142000000049
524	5	9	5	AYACUCHO	SUCRE	HUACAÑA	-13.1631107000000007	-74.2246142000000049
525	5	9	6	AYACUCHO	SUCRE	MORCOLLA	-13.1631107000000007	-74.2246142000000049
526	5	9	7	AYACUCHO	SUCRE	PAICO	-13.1631107000000007	-74.2246142000000049
527	5	9	8	AYACUCHO	SUCRE	SAN PEDRO DE LARCAY	-13.1631107000000007	-74.2246142000000049
528	5	9	9	AYACUCHO	SUCRE	SAN SALVADOR DE QUIJE	-13.1631107000000007	-74.2246142000000049
529	5	9	10	AYACUCHO	SUCRE	SANTIAGO DE PAUCARAY	-13.1631107000000007	-74.2246142000000049
530	5	9	11	AYACUCHO	SUCRE	SORAS	-13.1631107000000007	-74.2246142000000049
531	5	10	1	AYACUCHO	VICTOR FAJARDO	HUANCAPI	-13.1631107000000007	-74.2246142000000049
532	5	10	2	AYACUCHO	VICTOR FAJARDO	ALCAMENCA	-13.1631107000000007	-74.2246142000000049
533	5	10	3	AYACUCHO	VICTOR FAJARDO	APONGO	-13.1631107000000007	-74.2246142000000049
534	5	10	4	AYACUCHO	VICTOR FAJARDO	ASQUIPATA	-13.1631107000000007	-74.2246142000000049
535	5	10	5	AYACUCHO	VICTOR FAFARDO	CANARIA	-13.1631107000000007	-74.2246142000000049
536	5	10	6	AYACUCHO	VICTOR FAJARDO	CAYARA	-13.1631107000000007	-74.2246142000000049
537	5	10	7	AYACUCHO	VICTOR FAJARDO	COLCA	-13.1631107000000007	-74.2246142000000049
538	5	10	8	AYACUCHO	VICTOR FAJARDO	HUAMANQUIQUIA	-13.1631107000000007	-74.2246142000000049
539	5	10	9	AYACUCHO	VICTOR FAJARDO	HUANCARAYLLA	-13.1631107000000007	-74.2246142000000049
540	5	10	10	AYACUCHO	VICTOR FAJARDO	HUAYA	-13.1631107000000007	-74.2246142000000049
541	5	10	11	AYACUCHO	VICTOR FAJARDO	SARHUA	-13.1631107000000007	-74.2246142000000049
542	5	10	12	AYACUCHO	VICTOR FAJARDO	VILCANCHOS	-13.1631107000000007	-74.2246142000000049
543	5	11	1	AYACUCHO	VILCAS HUAMAN	VILCAS HUAMAN	-13.1631107000000007	-74.2246142000000049
544	5	11	2	AYACUCHO	VILCAS HUAMAN	ACCOMARCA	-13.1631107000000007	-74.2246142000000049
545	5	11	3	AYACUCHO	VILCAS HUAMAN	CARHUANCA	-13.1631107000000007	-74.2246142000000049
546	5	11	4	AYACUCHO	VILCAS HUAMAN	CONCEPCION	-13.1631107000000007	-74.2246142000000049
547	5	11	5	AYACUCHO	VILCAS HUAMAN	HUAMBALPA	-13.1631107000000007	-74.2246142000000049
548	5	11	6	AYACUCHO	VILCAS HUAMAN	INDEPENDENCIA	-13.1631107000000007	-74.2246142000000049
549	5	11	7	AYACUCHO	VILCAS HUAMAN	SAURAMA	-13.1631107000000007	-74.2246142000000049
550	5	11	8	AYACUCHO	VILCAS HUAMAN	VISCHONGO	-13.1631107000000007	-74.2246142000000049
319	3	7	3	APURIMAC	GRAU	GAMARRA	-14.3267912000000006	-73.1821623000000017
251	3	1	1	APURIMAC	ABANCAY	ABANCAY	-14.3267912000000006	-73.1821623000000017
252	3	1	2	APURIMAC	ABANCAY	CHACOCHE	-14.3267912000000006	-73.1821623000000017
253	3	1	3	APURIMAC	ABANCAY	CIRCA	-14.3267912000000006	-73.1821623000000017
254	3	1	4	APURIMAC	ABANCAY	CURAHUASI	-14.3267912000000006	-73.1821623000000017
255	3	1	5	APURIMAC	ABANCAY	HUANIPACA	-14.3267912000000006	-73.1821623000000017
256	3	1	6	APURIMAC	ABANCAY	LAMBRAMA	-14.3267912000000006	-73.1821623000000017
257	3	1	7	APURIMAC	ABANCAY	PICHIRHUA	-14.3267912000000006	-73.1821623000000017
258	3	1	8	APURIMAC	ABANCAY	SAN PEDRO DE CACHORA	-14.3267912000000006	-73.1821623000000017
259	3	1	9	APURIMAC	ABANCAY	TAMBURCO	-14.3267912000000006	-73.1821623000000017
260	3	2	1	APURIMAC	ANDAHUAYLAS	ANDAHUAYLAS	-14.3267912000000006	-73.1821623000000017
261	3	2	2	APURIMAC	ANDAHUAYLAS	ANDARAPA	-14.3267912000000006	-73.1821623000000017
262	3	2	3	APURIMAC	ANDAHUAYLAS	CHIARA	-14.3267912000000006	-73.1821623000000017
263	3	2	4	APURIMAC	ANDAHUAYLAS	HUANCARAMA	-14.3267912000000006	-73.1821623000000017
264	3	2	5	APURIMAC	ANDAHUAYLAS	HUANCARAY	-14.3267912000000006	-73.1821623000000017
265	3	2	6	APURIMAC	ANDAHUAYLAS	HUAYANA	-14.3267912000000006	-73.1821623000000017
266	3	2	7	APURIMAC	ANDAHUAYLAS	KISHUARA	-14.3267912000000006	-73.1821623000000017
267	3	2	8	APURIMAC	ANDAHUAYLAS	PACOBAMBA	-14.3267912000000006	-73.1821623000000017
268	3	2	9	APURIMAC	ANDAHUAYLAS	PACUCHA	-14.3267912000000006	-73.1821623000000017
269	3	2	10	APURIMAC	ANDAHUAYLAS	PAMPACHIRI	-14.3267912000000006	-73.1821623000000017
270	3	2	11	APURIMAC	ANDAHUAYLAS	POMACOCHA	-14.3267912000000006	-73.1821623000000017
271	3	2	12	APURIMAC	ANDAHUAYLAS	SAN ANTONIO DE CACHI	-14.3267912000000006	-73.1821623000000017
272	3	2	13	APURIMAC	ANDAHUAYLAS	SAN JERONIMO	-14.3267912000000006	-73.1821623000000017
273	3	2	14	APURIMAC	ANDAHUAYLAS	SAN MIGUEL DE CHACCRAMPA	-14.3267912000000006	-73.1821623000000017
274	3	2	15	APURIMAC	ANDAHUAYLAS	SANTA MARIA DE CHICMO	-14.3267912000000006	-73.1821623000000017
275	3	2	16	APURIMAC	ANDAHUAYLAS	TALAVERA	-14.3267912000000006	-73.1821623000000017
276	3	2	17	APURIMAC	ANDAHUAYLAS	TUMAY HUARACA	-14.3267912000000006	-73.1821623000000017
277	3	2	18	APURIMAC	ANDAHUAYLAS	TURPO	-14.3267912000000006	-73.1821623000000017
278	3	2	19	APURIMAC	ANDAHUAYLAS	KAQUIABAMBA	-14.3267912000000006	-73.1821623000000017
279	3	3	1	APURIMAC	ANTABAMBA	ANTABAMBA	-14.3267912000000006	-73.1821623000000017
280	3	3	2	APURIMAC	ANTABAMBA	EL ORO	-14.3267912000000006	-73.1821623000000017
281	3	3	3	APURIMAC	ANTABAMBA	HUAQUIRCA	-14.3267912000000006	-73.1821623000000017
282	3	3	4	APURIMAC	ANTABAMBA	JUAN ESPINOZA MEDRANO	-14.3267912000000006	-73.1821623000000017
283	3	3	5	APURIMAC	ANTABAMBA	OROPESA	-14.3267912000000006	-73.1821623000000017
284	3	3	6	APURIMAC	ANTABAMBA	PACHACONAS	-14.3267912000000006	-73.1821623000000017
285	3	3	7	APURIMAC	ANTABAMBA	SABAINO	-14.3267912000000006	-73.1821623000000017
286	3	4	1	APURIMAC	AYMARAES	CHALHUANCA	-14.3267912000000006	-73.1821623000000017
287	3	4	2	APURIMAC	AYMARAES	CAPAYA	-14.3267912000000006	-73.1821623000000017
288	3	4	3	APURIMAC	AYMARAES	CARAYBAMBA	-14.3267912000000006	-73.1821623000000017
289	3	4	4	APURIMAC	AYMARAES	CHAPIMARCA	-14.3267912000000006	-73.1821623000000017
290	3	4	5	APURIMAC	AYMARAES	COLCABAMBA	-14.3267912000000006	-73.1821623000000017
291	3	4	6	APURIMAC	AYMARAES	COTARUSE	-14.3267912000000006	-73.1821623000000017
292	3	4	7	APURIMAC	AYMARAES	HUAYLLO	-14.3267912000000006	-73.1821623000000017
293	3	4	8	APURIMAC	AYMARAES	JUSTO APU SAHUARAURA	-14.3267912000000006	-73.1821623000000017
294	3	4	9	APURIMAC	AYMARAES	LUCRE	-14.3267912000000006	-73.1821623000000017
295	3	4	10	APURIMAC	AYMARAES	POCOHUANCA	-14.3267912000000006	-73.1821623000000017
296	3	4	11	APURIMAC	AYMARAES	SAN JUAN DE CHACÑA	-14.3267912000000006	-73.1821623000000017
297	3	4	12	APURIMAC	AYMARAES	SAÑAYCA	-14.3267912000000006	-73.1821623000000017
298	3	4	13	APURIMAC	AYMARAES	SORAYA	-14.3267912000000006	-73.1821623000000017
299	3	4	14	APURIMAC	AYMARAES	TAPAIRIHUA	-14.3267912000000006	-73.1821623000000017
300	3	4	15	APURIMAC	AYMARAES	TINTAY	-14.3267912000000006	-73.1821623000000017
301	3	4	16	APURIMAC	AYMARAES	TORAYA	-14.3267912000000006	-73.1821623000000017
302	3	4	17	APURIMAC	AYMARAES	YANACA	-14.3267912000000006	-73.1821623000000017
303	3	5	1	APURIMAC	COTABAMBAS	TAMBOBAMBA	-14.3267912000000006	-73.1821623000000017
304	3	5	2	APURIMAC	COTABAMBAS	COTABAMBAS	-14.3267912000000006	-73.1821623000000017
305	3	5	3	APURIMAC	COTABAMBAS	COYLLURQUI	-14.3267912000000006	-73.1821623000000017
306	3	5	4	APURIMAC	COTABAMBAS	HAQUIRA	-14.3267912000000006	-73.1821623000000017
307	3	5	5	APURIMAC	COTABAMBAS	MARA	-14.3267912000000006	-73.1821623000000017
308	3	5	6	APURIMAC	COTABAMBAS	CHALLHUAHUACHO	-14.3267912000000006	-73.1821623000000017
309	3	6	1	APURIMAC	CHINCHEROS	CHINCHEROS	-14.3267912000000006	-73.1821623000000017
310	3	6	2	APURIMAC	CHINCHEROS	ANCO_HUALLO	-14.3267912000000006	-73.1821623000000017
311	3	6	3	APURIMAC	CHINCHEROS	COCHARCAS	-14.3267912000000006	-73.1821623000000017
312	3	6	4	APURIMAC	CHINCHEROS	HUACCANA	-14.3267912000000006	-73.1821623000000017
313	3	6	5	APURIMAC	CHINCHEROS	OCOBAMBA	-14.3267912000000006	-73.1821623000000017
314	3	6	6	APURIMAC	CHINCHEROS	ONGOY	-14.3267912000000006	-73.1821623000000017
315	3	6	7	APURIMAC	CHINCHEROS	URANMARCA	-14.3267912000000006	-73.1821623000000017
316	3	6	8	APURIMAC	CHINCHEROS	RANRACANCHA	-14.3267912000000006	-73.1821623000000017
317	3	7	1	APURIMAC	GRAU	CHUQUIBAMBILLA	-14.3267912000000006	-73.1821623000000017
318	3	7	2	APURIMAC	GRAU	CURPAHUASI	-14.3267912000000006	-73.1821623000000017
320	3	7	4	APURIMAC	GRAU	HUAYLLATI	-14.3267912000000006	-73.1821623000000017
321	3	7	5	APURIMAC	GRAU	MAMARA	-14.3267912000000006	-73.1821623000000017
322	3	7	6	APURIMAC	GRAU	MICAELA BASTIDAS	-14.3267912000000006	-73.1821623000000017
323	3	7	7	APURIMAC	GRAU	PATAYPAMPA	-14.3267912000000006	-73.1821623000000017
324	3	7	8	APURIMAC	GRAU	PROGRESO	-14.3267912000000006	-73.1821623000000017
325	3	7	9	APURIMAC	GRAU	SAN ANTONIO	-14.3267912000000006	-73.1821623000000017
326	3	7	10	APURIMAC	GRAU	SANTA ROSA	-14.3267912000000006	-73.1821623000000017
327	3	7	11	APURIMAC	GRAU	TURPAY	-14.3267912000000006	-73.1821623000000017
328	3	7	12	APURIMAC	GRAU	VILCABAMBA	-14.3267912000000006	-73.1821623000000017
329	3	7	13	APURIMAC	GRAU	VIRUNDO	-14.3267912000000006	-73.1821623000000017
330	3	7	14	APURIMAC	GRAU	CURASCO	-14.3267912000000006	-73.1821623000000017
1128	13	1	1	LA LIBERTAD	TRUJILLO	TRUJILLO	-8.11248900000000006	-79.0288469000000049
1129	13	1	2	LA LIBERTAD	TRUJILLO	EL PORVENIR	-8.11248900000000006	-79.0288469000000049
1130	13	1	3	LA LIBERTAD	TRUJILLO	FLORENCIA DE MORA	-8.11248900000000006	-79.0288469000000049
1131	13	1	4	LA LIBERTAD	TRUJILLO	HUANCHACO	-8.11248900000000006	-79.0288469000000049
1132	13	1	5	LA LIBERTAD	TRUJILLO	LA ESPERANZA	-8.11248900000000006	-79.0288469000000049
1133	13	1	6	LA LIBERTAD	TRUJILLO	LAREDO	-8.11248900000000006	-79.0288469000000049
1134	13	1	7	LA LIBERTAD	TRUJILLO	MOCHE	-8.11248900000000006	-79.0288469000000049
1135	13	1	8	LA LIBERTAD	TRUJILLO	POROTO	-8.11248900000000006	-79.0288469000000049
1136	13	1	9	LA LIBERTAD	TRUJILLO	SALAVERRY	-8.11248900000000006	-79.0288469000000049
1137	13	1	10	LA LIBERTAD	TRUJILLO	SIMBAL	-8.11248900000000006	-79.0288469000000049
1138	13	1	11	LA LIBERTAD	TRUJILLO	VICTOR LARCO HERRERA	-8.11248900000000006	-79.0288469000000049
1139	13	2	1	LA LIBERTAD	ASCOPE	ASCOPE	-8.11248900000000006	-79.0288469000000049
1140	13	2	2	LA LIBERTAD	ASCOPE	CHICAMA	-8.11248900000000006	-79.0288469000000049
1141	13	2	3	LA LIBERTAD	ASCOPE	CHOCOPE	-8.11248900000000006	-79.0288469000000049
1142	13	2	4	LA LIBERTAD	ASCOPE	MAGDALENA DE CAO	-8.11248900000000006	-79.0288469000000049
1143	13	2	5	LA LIBERTAD	ASCOPE	PAIJAN	-8.11248900000000006	-79.0288469000000049
1144	13	2	6	LA LIBERTAD	ASCOPE	RAZURI	-8.11248900000000006	-79.0288469000000049
1145	13	2	7	LA LIBERTAD	ASCOPE	SANTIAGO DE CAO	-8.11248900000000006	-79.0288469000000049
1146	13	2	8	LA LIBERTAD	ASCOPE	CASA GRANDE	-8.11248900000000006	-79.0288469000000049
1147	13	3	1	LA LIBERTAD	BOLIVAR	BOLIVAR	-8.11248900000000006	-79.0288469000000049
1148	13	3	2	LA LIBERTAD	BOLIVAR	BAMBAMARCA	-8.11248900000000006	-79.0288469000000049
1149	13	3	3	LA LIBERTAD	BOLIVAR	CONDORMARCA	-8.11248900000000006	-79.0288469000000049
1150	13	3	4	LA LIBERTAD	BOLIVAR	LONGOTEA	-8.11248900000000006	-79.0288469000000049
1151	13	3	5	LA LIBERTAD	BOLIVAR	UCHUMARCA	-8.11248900000000006	-79.0288469000000049
1152	13	3	6	LA LIBERTAD	BOLIVAR	UCUNCHA	-8.11248900000000006	-79.0288469000000049
1153	13	4	1	LA LIBERTAD	CHEPEN	CHEPEN	-8.11248900000000006	-79.0288469000000049
1154	13	4	2	LA LIBERTAD	CHEPEN	PACANGA	-8.11248900000000006	-79.0288469000000049
1155	13	4	3	LA LIBERTAD	CHEPEN	PUEBLO NUEVO	-8.11248900000000006	-79.0288469000000049
1156	13	5	1	LA LIBERTAD	JULCAN	JULCAN	-8.11248900000000006	-79.0288469000000049
1157	13	5	2	LA LIBERTAD	JULCAN	CALAMARCA	-8.11248900000000006	-79.0288469000000049
1158	13	5	3	LA LIBERTAD	JULCAN	CARABAMBA	-8.11248900000000006	-79.0288469000000049
1159	13	5	4	LA LIBERTAD	JULCAN	HUASO	-8.11248900000000006	-79.0288469000000049
1160	13	6	1	LA LIBERTAD	OTUZCO	OTUZCO	-8.11248900000000006	-79.0288469000000049
1161	13	6	2	LA LIBERTAD	OTUZCO	AGALLPAMPA	-8.11248900000000006	-79.0288469000000049
1162	13	6	4	LA LIBERTAD	OTUZCO	CHARAT	-8.11248900000000006	-79.0288469000000049
1163	13	6	5	LA LIBERTAD	OTUZCO	HUARANCHAL	-8.11248900000000006	-79.0288469000000049
1164	13	6	6	LA LIBERTAD	OTUZCO	LA CUESTA	-8.11248900000000006	-79.0288469000000049
1165	13	6	8	LA LIBERTAD	OTUZCO	MACHE	-8.11248900000000006	-79.0288469000000049
1166	13	6	10	LA LIBERTAD	OTUZCO	PARANDAY	-8.11248900000000006	-79.0288469000000049
1167	13	6	11	LA LIBERTAD	OTUZCO	SALPO	-8.11248900000000006	-79.0288469000000049
1168	13	6	13	LA LIBERTAD	OTUZCO	SINSICAP	-8.11248900000000006	-79.0288469000000049
1169	13	6	14	LA LIBERTAD	OTUZCO	USQUIL	-8.11248900000000006	-79.0288469000000049
1170	13	7	1	LA LIBERTAD	PACASMAYO	SAN PEDRO DE LLOC	-8.11248900000000006	-79.0288469000000049
1171	13	7	2	LA LIBERTAD	PACASMAYO	GUADALUPE	-8.11248900000000006	-79.0288469000000049
1172	13	7	3	LA LIBERTAD	PACASMAYO	JEQUETEPEQUE	-8.11248900000000006	-79.0288469000000049
1173	13	7	4	LA LIBERTAD	PACASMAYO	PACASMAYO	-8.11248900000000006	-79.0288469000000049
1174	13	7	5	LA LIBERTAD	PACASMAYO	SAN JOSE	-8.11248900000000006	-79.0288469000000049
1175	13	8	1	LA LIBERTAD	PATAZ	TAYABAMBA	-8.11248900000000006	-79.0288469000000049
1176	13	8	2	LA LIBERTAD	PATAZ	BULDIBUYO	-8.11248900000000006	-79.0288469000000049
1177	13	8	3	LA LIBERTAD	PATAZ	CHILLIA	-8.11248900000000006	-79.0288469000000049
1178	13	8	4	LA LIBERTAD	PATAZ	HUANCASPATA	-8.11248900000000006	-79.0288469000000049
1179	13	8	5	LA LIBERTAD	PATAZ	HUAYLILLAS	-8.11248900000000006	-79.0288469000000049
1180	13	8	6	LA LIBERTAD	PATAZ	HUAYO	-8.11248900000000006	-79.0288469000000049
1181	13	8	7	LA LIBERTAD	PATAZ	ONGON	-8.11248900000000006	-79.0288469000000049
1182	13	8	8	LA LIBERTAD	PATAZ	PARCOY	-8.11248900000000006	-79.0288469000000049
1183	13	8	9	LA LIBERTAD	PATAZ	PATAZ	-8.11248900000000006	-79.0288469000000049
1184	13	8	10	LA LIBERTAD	PATAZ	PIAS	-8.11248900000000006	-79.0288469000000049
1185	13	8	11	LA LIBERTAD	PATAZ	SANTIAGO DE CHALLAS	-8.11248900000000006	-79.0288469000000049
1186	13	8	12	LA LIBERTAD	PATAZ	TAURIJA	-8.11248900000000006	-79.0288469000000049
1187	13	8	13	LA LIBERTAD	PATAZ	URPAY	-8.11248900000000006	-79.0288469000000049
1188	13	9	1	LA LIBERTAD	SANCHEZ CARRION	HUAMACHUCO	-8.11248900000000006	-79.0288469000000049
1189	13	9	2	LA LIBERTAD	SANCHEZ CARRION	CHUGAY	-8.11248900000000006	-79.0288469000000049
1190	13	9	3	LA LIBERTAD	SANCHEZ CARRION	COCHORCO	-8.11248900000000006	-79.0288469000000049
1191	13	9	4	LA LIBERTAD	SANCHEZ CARRION	CURGOS	-8.11248900000000006	-79.0288469000000049
1192	13	9	5	LA LIBERTAD	SANCHEZ CARRION	MARCABAL	-8.11248900000000006	-79.0288469000000049
1193	13	9	6	LA LIBERTAD	SANCHEZ CARRION	SANAGORAN	-8.11248900000000006	-79.0288469000000049
1194	13	9	7	LA LIBERTAD	SANCHEZ CARRION	SARIN	-8.11248900000000006	-79.0288469000000049
1195	13	9	8	LA LIBERTAD	SANCHEZ CARRION	SARTIMBAMBA	-8.11248900000000006	-79.0288469000000049
1196	13	10	1	LA LIBERTAD	SANTIAGO DE CHUCO	SANTIAGO DE CHUCO	-8.11248900000000006	-79.0288469000000049
1197	13	10	2	LA LIBERTAD	SANTIAGO DE CHUCO	ANGASMARCA	-8.11248900000000006	-79.0288469000000049
1198	13	10	3	LA LIBERTAD	SANTIAGO DE CHUCO	CACHICADAN	-8.11248900000000006	-79.0288469000000049
1199	13	10	4	LA LIBERTAD	SANTIAGO DE CHUCO	MOLLEBAMBA	-8.11248900000000006	-79.0288469000000049
1200	13	10	5	LA LIBERTAD	SANTIAGO DE CHUCO	MOLLEPATA	-8.11248900000000006	-79.0288469000000049
1201	13	10	6	LA LIBERTAD	SANTIAGO DE CHUCO	QUIRUVILCA	-8.11248900000000006	-79.0288469000000049
1202	13	10	7	LA LIBERTAD	SANTIAGO DE CHUCO	SANTA CRUZ DE CHUCA	-8.11248900000000006	-79.0288469000000049
1203	13	10	8	LA LIBERTAD	SANTIAGO DE CHUCO	SITABAMBA	-8.11248900000000006	-79.0288469000000049
1204	13	11	1	LA LIBERTAD	GRAN CHIMU	CASCAS	-8.11248900000000006	-79.0288469000000049
1205	13	11	2	LA LIBERTAD	GRAN CHIMU	LUCMA	-8.11248900000000006	-79.0288469000000049
1206	13	11	3	LA LIBERTAD	GRAN CHIMU	COMPIN	-8.11248900000000006	-79.0288469000000049
1207	13	11	4	LA LIBERTAD	GRAN CHIMU	SAYAPULLO	-8.11248900000000006	-79.0288469000000049
1208	13	12	1	LA LIBERTAD	VIRU	VIRU	-8.11248900000000006	-79.0288469000000049
1209	13	12	2	LA LIBERTAD	VIRU	CHAO	-8.11248900000000006	-79.0288469000000049
1210	13	12	3	LA LIBERTAD	VIRU	GUADALUPITO	-8.11248900000000006	-79.0288469000000049
85	2	1	1	ANCASH	HUARAZ	HUARAZ	-9.07503419999999927	-78.593697199999994
86	2	1	2	ANCASH	HUARAZ	COCHABAMBA	-9.07503419999999927	-78.593697199999994
87	2	1	3	ANCASH	HUARAZ	COLCABAMBA	-9.07503419999999927	-78.593697199999994
88	2	1	4	ANCASH	HUARAZ	HUANCHAY	-9.07503419999999927	-78.593697199999994
89	2	1	5	ANCASH	HUARAZ	INDEPENDENCIA	-9.07503419999999927	-78.593697199999994
90	2	1	6	ANCASH	HUARAZ	JANGAS	-9.07503419999999927	-78.593697199999994
91	2	1	7	ANCASH	HUARAZ	LA LIBERTAD	-9.07503419999999927	-78.593697199999994
92	2	1	8	ANCASH	HUARAZ	OLLEROS	-9.07503419999999927	-78.593697199999994
93	2	1	9	ANCASH	HUARAZ	PAMPAS	-9.07503419999999927	-78.593697199999994
94	2	1	10	ANCASH	HUARAZ	PARIACOTO	-9.07503419999999927	-78.593697199999994
95	2	1	11	ANCASH	HUARAZ	PIRA	-9.07503419999999927	-78.593697199999994
96	2	1	12	ANCASH	HUARAZ	TARICA	-9.07503419999999927	-78.593697199999994
97	2	2	1	ANCASH	AIJA	AIJA	-9.07503419999999927	-78.593697199999994
98	2	2	2	ANCASH	AIJA	CORIS	-9.07503419999999927	-78.593697199999994
99	2	2	3	ANCASH	AIJA	HUACLLAN	-9.07503419999999927	-78.593697199999994
100	2	2	4	ANCASH	AIJA	LA MERCED	-9.07503419999999927	-78.593697199999994
101	2	2	5	ANCASH	AIJA	SUCCHA	-9.07503419999999927	-78.593697199999994
102	2	3	1	ANCASH	ANTONIO RAYMONDI	LLAMELLIN	-9.07503419999999927	-78.593697199999994
103	2	3	2	ANCASH	ANTONIO RAYMONDI	ACZO	-9.07503419999999927	-78.593697199999994
104	2	3	3	ANCASH	ANTONIO RAYMONDI	CHACCHO	-9.07503419999999927	-78.593697199999994
105	2	3	4	ANCASH	ANTONIO RAYMONDI	CHINGAS	-9.07503419999999927	-78.593697199999994
106	2	3	5	ANCASH	ANTONIO RAYMONDI	MIRGAS	-9.07503419999999927	-78.593697199999994
107	2	3	6	ANCASH	ANTONIO RAYMONDI	SAN JUAN DE RONTOY	-9.07503419999999927	-78.593697199999994
108	2	4	1	ANCASH	ASUNCION	CHACAS	-9.07503419999999927	-78.593697199999994
109	2	4	2	ANCASH	ASUNCION	ACOCHACA	-9.07503419999999927	-78.593697199999994
110	2	5	1	ANCASH	BOLOGNESI	CHIQUIAN	-9.07503419999999927	-78.593697199999994
111	2	5	2	ANCASH	BOLOGNESI	ABELARDO PARDO LEZAMETA	-9.07503419999999927	-78.593697199999994
112	2	5	3	ANCASH	BOLOGNESI	ANTONIO RAYMONDI	-9.07503419999999927	-78.593697199999994
113	2	5	4	ANCASH	BOLOGNESI	AQUIA	-9.07503419999999927	-78.593697199999994
114	2	5	5	ANCASH	BOLOGNESI	CAJACAY	-9.07503419999999927	-78.593697199999994
115	2	5	6	ANCASH	BOLOGNESI	CANIS	-9.07503419999999927	-78.593697199999994
116	2	5	7	ANCASH	BOLOGNESI	COLQUIOC	-9.07503419999999927	-78.593697199999994
117	2	5	8	ANCASH	BOLOGNESI	HUALLANCA	-9.07503419999999927	-78.593697199999994
118	2	5	9	ANCASH	BOLOGNESI	HUASTA	-9.07503419999999927	-78.593697199999994
119	2	5	10	ANCASH	BOLOGNESI	HUAYLLACAYAN	-9.07503419999999927	-78.593697199999994
120	2	5	11	ANCASH	BOLOGNESI	LA PRIMAVERA	-9.07503419999999927	-78.593697199999994
121	2	5	12	ANCASH	BOLOGNESI	MANGAS	-9.07503419999999927	-78.593697199999994
122	2	5	13	ANCASH	BOLOGNESI	PACLLON	-9.07503419999999927	-78.593697199999994
123	2	5	14	ANCASH	BOLOGNESI	SAN MIGUEL DE CORPANQUI	-9.07503419999999927	-78.593697199999994
124	2	5	15	ANCASH	BOLOGNESI	TICLLOS	-9.07503419999999927	-78.593697199999994
125	2	6	1	ANCASH	CARHUAZ	CARHUAZ	-9.07503419999999927	-78.593697199999994
126	2	6	2	ANCASH	CARHUAZ	ACOPAMPA	-9.07503419999999927	-78.593697199999994
127	2	6	3	ANCASH	CARHUAZ	AMASHCA	-9.07503419999999927	-78.593697199999994
128	2	6	4	ANCASH	CARHUAZ	ANTA	-9.07503419999999927	-78.593697199999994
129	2	6	5	ANCASH	CARHUAZ	ATAQUERO	-9.07503419999999927	-78.593697199999994
130	2	6	6	ANCASH	CARHUAZ	MARCARA	-9.07503419999999927	-78.593697199999994
131	2	6	7	ANCASH	CARHUAZ	PARIAHUANCA	-9.07503419999999927	-78.593697199999994
132	2	6	8	ANCASH	CARHUAZ	SAN MIGUEL DE ACO	-9.07503419999999927	-78.593697199999994
133	2	6	9	ANCASH	CARHUAZ	SHILLA	-9.07503419999999927	-78.593697199999994
134	2	6	10	ANCASH	CARHUAZ	TINCO	-9.07503419999999927	-78.593697199999994
135	2	6	11	ANCASH	CARHUAZ	YUNGAR	-9.07503419999999927	-78.593697199999994
136	2	7	1	ANCASH	CARLOS FERMIN FITZCARRALD	SAN LUIS	-9.07503419999999927	-78.593697199999994
137	2	7	2	ANCASH	CARLOS FERMIN FITZCARRALD	SAN NICOLAS	-9.07503419999999927	-78.593697199999994
138	2	7	3	ANCASH	CARLOS FERMIN FITZCARRALD	YAUYA	-9.07503419999999927	-78.593697199999994
139	2	8	1	ANCASH	CASMA	CASMA	-9.07503419999999927	-78.593697199999994
140	2	8	2	ANCASH	CASMA	BUENA VISTA ALTA	-9.07503419999999927	-78.593697199999994
141	2	8	3	ANCASH	CASMA	COMANDANTE NOEL	-9.07503419999999927	-78.593697199999994
142	2	8	4	ANCASH	CASMA	YAUTAN	-9.07503419999999927	-78.593697199999994
143	2	9	1	ANCASH	CORONGO	CORONGO	-9.07503419999999927	-78.593697199999994
144	2	9	2	ANCASH	CORONGO	ACO	-9.07503419999999927	-78.593697199999994
145	2	9	3	ANCASH	CORONGO	BAMBAS	-9.07503419999999927	-78.593697199999994
146	2	9	4	ANCASH	CORONGO	CUSCA	-9.07503419999999927	-78.593697199999994
147	2	9	5	ANCASH	CORONGO	LA PAMPA	-9.07503419999999927	-78.593697199999994
148	2	9	6	ANCASH	CORONGO	YANAC	-9.07503419999999927	-78.593697199999994
149	2	9	7	ANCASH	CORONGO	YUPAN	-9.07503419999999927	-78.593697199999994
150	2	10	1	ANCASH	HUARI	HUARI	-9.07503419999999927	-78.593697199999994
151	2	10	2	ANCASH	HUARI	ANRA	-9.07503419999999927	-78.593697199999994
152	2	10	3	ANCASH	HUARI	CAJAY	-9.07503419999999927	-78.593697199999994
153	2	10	4	ANCASH	HUARI	CHAVIN DE HUANTAR	-9.07503419999999927	-78.593697199999994
154	2	10	5	ANCASH	HUARI	HUACACHI	-9.07503419999999927	-78.593697199999994
155	2	10	6	ANCASH	HUARI	HUACCHIS	-9.07503419999999927	-78.593697199999994
156	2	10	7	ANCASH	HUARI	HUACHIS	-9.07503419999999927	-78.593697199999994
157	2	10	8	ANCASH	HUARI	HUANTAR	-9.07503419999999927	-78.593697199999994
158	2	10	9	ANCASH	HUARI	MASIN	-9.07503419999999927	-78.593697199999994
159	2	10	10	ANCASH	HUARI	PAUCAS	-9.07503419999999927	-78.593697199999994
160	2	10	11	ANCASH	HUARI	PONTO	-9.07503419999999927	-78.593697199999994
161	2	10	12	ANCASH	HUARI	RAHUAPAMPA	-9.07503419999999927	-78.593697199999994
162	2	10	13	ANCASH	HUARI	RAPAYAN	-9.07503419999999927	-78.593697199999994
163	2	10	14	ANCASH	HUARI	SAN MARCOS	-9.07503419999999927	-78.593697199999994
164	2	10	15	ANCASH	HUARI	SAN PEDRO DE CHANA	-9.07503419999999927	-78.593697199999994
165	2	10	16	ANCASH	HUARI	UCO	-9.07503419999999927	-78.593697199999994
166	2	11	1	ANCASH	HUARMEY	HUARMEY	-9.07503419999999927	-78.593697199999994
167	2	11	2	ANCASH	HUARMEY	COCHAPETI	-9.07503419999999927	-78.593697199999994
168	2	11	3	ANCASH	HUARMEY	CULEBRAS	-9.07503419999999927	-78.593697199999994
169	2	11	4	ANCASH	HUARMEY	HUAYAN	-9.07503419999999927	-78.593697199999994
170	2	11	5	ANCASH	HUARMEY	MALVAS	-9.07503419999999927	-78.593697199999994
171	2	12	1	ANCASH	HUAYLAS	CARAZ	-9.07503419999999927	-78.593697199999994
172	2	12	2	ANCASH	HUAYLAS	HUALLANCA	-9.07503419999999927	-78.593697199999994
173	2	12	3	ANCASH	HUAYLAS	HUATA	-9.07503419999999927	-78.593697199999994
174	2	12	4	ANCASH	HUAYLAS	HUAYLAS	-9.07503419999999927	-78.593697199999994
175	2	12	5	ANCASH	HUAYLAS	MATO	-9.07503419999999927	-78.593697199999994
176	2	12	6	ANCASH	HUAYLAS	PAMPAROMAS	-9.07503419999999927	-78.593697199999994
177	2	12	7	ANCASH	HUAYLAS	PUEBLO LIBRE	-9.07503419999999927	-78.593697199999994
178	2	12	8	ANCASH	HUAYLAS	SANTA CRUZ	-9.07503419999999927	-78.593697199999994
179	2	12	9	ANCASH	HUAYLAS	SANTO TORIBIO	-9.07503419999999927	-78.593697199999994
180	2	12	10	ANCASH	HUAYLAS	YURACMARCA	-9.07503419999999927	-78.593697199999994
181	2	13	1	ANCASH	MARISCAL LUZURIAGA	PISCOBAMBA	-9.07503419999999927	-78.593697199999994
182	2	13	2	ANCASH	MARISCAL LUZURIAGA	CASCA	-9.07503419999999927	-78.593697199999994
183	2	13	3	ANCASH	MARISCAL LUZURIAGA	ELEAZAR GUZMAN BARRON	-9.07503419999999927	-78.593697199999994
184	2	13	4	ANCASH	MARISCAL LUZURIAGA	FIDEL OLIVAS ESCUDERO	-9.07503419999999927	-78.593697199999994
185	2	13	5	ANCASH	MARISCAL LUZURIAGA	LLAMA	-9.07503419999999927	-78.593697199999994
186	2	13	6	ANCASH	MARISCAL LUZURIAGA	LLUMPA	-9.07503419999999927	-78.593697199999994
187	2	13	7	ANCASH	MARISCAL LUZURIAGA	LUCMA	-9.07503419999999927	-78.593697199999994
188	2	13	8	ANCASH	MARISCAL LUZURIAGA	MUSGA	-9.07503419999999927	-78.593697199999994
189	2	14	1	ANCASH	OCROS	OCROS	-9.07503419999999927	-78.593697199999994
190	2	14	2	ANCASH	OCROS	ACAS	-9.07503419999999927	-78.593697199999994
191	2	14	3	ANCASH	OCROS	CAJAMARQUILLA	-9.07503419999999927	-78.593697199999994
231	2	18	8	ANCASH	SANTA	SANTA	-9.07503419999999927	-78.593697199999994
232	2	18	9	ANCASH	SANTA	NUEVO CHIMBOTE	-9.07503419999999927	-78.593697199999994
233	2	19	1	ANCASH	SIHUAS	SIHUAS	-9.07503419999999927	-78.593697199999994
234	2	19	2	ANCASH	SIHUAS	ACOBAMBA	-9.07503419999999927	-78.593697199999994
235	2	19	3	ANCASH	SIHUAS	ALFONSO UGARTE	-9.07503419999999927	-78.593697199999994
236	2	19	4	ANCASH	SIHUAS	CASHAPAMPA	-9.07503419999999927	-78.593697199999994
237	2	19	5	ANCASH	SIHUAS	CHINGALPO	-9.07503419999999927	-78.593697199999994
238	2	19	6	ANCASH	SIHUAS	HUAYLLABAMBA	-9.07503419999999927	-78.593697199999994
239	2	19	7	ANCASH	SIHUAS	QUICHES	-9.07503419999999927	-78.593697199999994
240	2	19	8	ANCASH	SIHUAS	RAGASH	-9.07503419999999927	-78.593697199999994
241	2	19	9	ANCASH	SIHUAS	SAN JUAN	-9.07503419999999927	-78.593697199999994
242	2	19	10	ANCASH	SIHUAS	SICSIBAMBA	-9.07503419999999927	-78.593697199999994
243	2	20	1	ANCASH	YUNGAY	YUNGAY	-9.07503419999999927	-78.593697199999994
244	2	20	2	ANCASH	YUNGAY	CASCAPARA	-9.07503419999999927	-78.593697199999994
245	2	20	3	ANCASH	YUNGAY	MANCOS	-9.07503419999999927	-78.593697199999994
246	2	20	4	ANCASH	YUNGAY	MATACOTO	-9.07503419999999927	-78.593697199999994
247	2	20	5	ANCASH	YUNGAY	QUILLO	-9.07503419999999927	-78.593697199999994
248	2	20	6	ANCASH	YUNGAY	RANRAHIRCA	-9.07503419999999927	-78.593697199999994
249	2	20	7	ANCASH	YUNGAY	SHUPLUY	-9.07503419999999927	-78.593697199999994
250	2	20	8	ANCASH	YUNGAY	YANAMA	-9.07503419999999927	-78.593697199999994
1530	20	1	1	PIURA	PIURA	PIURA	-5.19994729999999983	-80.633630299999993
1531	20	1	4	PIURA	PIURA	CASTILLA	-5.19994729999999983	-80.633630299999993
1532	20	1	5	PIURA	PIURA	CATACAOS	-5.19994729999999983	-80.633630299999993
1533	20	1	7	PIURA	PIURA	CURA MORI	-5.19994729999999983	-80.633630299999993
1534	20	1	8	PIURA	PIURA	EL TALLAN	-5.19994729999999983	-80.633630299999993
1535	20	1	9	PIURA	PIURA	LA ARENA	-5.19994729999999983	-80.633630299999993
1536	20	1	10	PIURA	PUIRA	LA UNION	-5.19994729999999983	-80.633630299999993
1537	20	1	11	PIURA	PIURA	LAS LOMAS	-5.19994729999999983	-80.633630299999993
1538	20	1	14	PIURA	PIURA	TAMBO GRANDE	-5.19994729999999983	-80.633630299999993
1539	20	2	1	PIURA	AYABACA	AYABACA	-5.19994729999999983	-80.633630299999993
1540	20	2	2	PIURA	AYABACA	FRIAS	-5.19994729999999983	-80.633630299999993
1541	20	2	3	PIURA	AYABACA	JILILI	-5.19994729999999983	-80.633630299999993
1542	20	2	4	PIURA	AYABACA	LAGUNAS	-5.19994729999999983	-80.633630299999993
1543	20	2	5	PIURA	AYABACA	MONTERO	-5.19994729999999983	-80.633630299999993
1544	20	2	6	PIURA	AYABACA	PACAIPAMPA	-5.19994729999999983	-80.633630299999993
1545	20	2	7	PIURA	AYABACA	PAIMAS	-5.19994729999999983	-80.633630299999993
1546	20	2	8	PIURA	AYABACA	SAPILLICA	-5.19994729999999983	-80.633630299999993
1547	20	2	9	PIURA	AYABACA	SICCHEZ	-5.19994729999999983	-80.633630299999993
1548	20	2	10	PIURA	AYABACA	SUYO	-5.19994729999999983	-80.633630299999993
1549	20	3	1	PIURA	HUANCABAMBA	HUANCABAMBA	-5.19994729999999983	-80.633630299999993
1550	20	3	2	PIURA	HUANCABAMBA	CANCHAQUE	-5.19994729999999983	-80.633630299999993
1551	20	3	3	PIURA	HUANCABAMBA	EL CARMEN DE LA FRONTERA	-5.19994729999999983	-80.633630299999993
1552	20	3	4	PIURA	HUANCABAMBA	HUARMACA	-5.19994729999999983	-80.633630299999993
1553	20	3	5	PIURA	HUANCABAMBA	LALAQUIZ	-5.19994729999999983	-80.633630299999993
1554	20	3	6	PIURA	HUANCABAMBA	SAN MIGUEL DE EL FAIQUE	-5.19994729999999983	-80.633630299999993
1555	20	3	7	PIURA	HUANCABAMBA	SONDOR	-5.19994729999999983	-80.633630299999993
1556	20	3	8	PIURA	HUANCABAMBA	SONDORILLO	-5.19994729999999983	-80.633630299999993
1557	20	4	1	PIURA	MORROPON	CHULUCANAS	-5.19994729999999983	-80.633630299999993
1558	20	4	2	PIURA	MORROPON	BUENOS AIRES	-5.19994729999999983	-80.633630299999993
1559	20	4	3	PIURA	MORROPON	CHALACO	-5.19994729999999983	-80.633630299999993
1560	20	4	4	PIURA	MORROPON	LA MATANZA	-5.19994729999999983	-80.633630299999993
1561	20	4	5	PIURA	MORROPON	MORROPON	-5.19994729999999983	-80.633630299999993
1562	20	4	6	PIURA	MORROPON	SALITRAL	-5.19994729999999983	-80.633630299999993
1563	20	4	7	PIURA	MORROPON	SAN JUAN DE BIGOTE	-5.19994729999999983	-80.633630299999993
1564	20	4	8	PIURA	MORROPON	SANTA CATALINA DE MOSSA	-5.19994729999999983	-80.633630299999993
1565	20	4	9	PIURA	MORROPON	SANTO DOMINGO	-5.19994729999999983	-80.633630299999993
1566	20	4	10	PIURA	MORROPON	YAMANGO	-5.19994729999999983	-80.633630299999993
1567	20	5	1	PIURA	PAITA	PAITA	-5.19994729999999983	-80.633630299999993
1568	20	5	2	PIURA	PAITA	AMOTAPE	-5.19994729999999983	-80.633630299999993
1569	20	5	3	PIURA	PAITA	ARENAL	-5.19994729999999983	-80.633630299999993
1570	20	5	4	PIURA	PAITA	COLAN	-5.19994729999999983	-80.633630299999993
1571	20	5	5	PIURA	PAITA	LA HUACA	-5.19994729999999983	-80.633630299999993
1572	20	5	6	PIURA	PAITA	TAMARINDO	-5.19994729999999983	-80.633630299999993
1573	20	5	7	PIURA	PAITA	VICHAYAL	-5.19994729999999983	-80.633630299999993
1574	20	6	1	PIURA	SULLANA	SULLANA	-5.19994729999999983	-80.633630299999993
1575	20	6	2	PIURA	SULLANA	BELLAVISTA	-5.19994729999999983	-80.633630299999993
1576	20	6	3	PIURA	SULLANA	IGNACIO ESCUDERO	-5.19994729999999983	-80.633630299999993
1577	20	6	4	PIURA	SULLANA	LANCONES	-5.19994729999999983	-80.633630299999993
1578	20	6	5	PIURA	SULLANA	MARCAVELICA	-5.19994729999999983	-80.633630299999993
1579	20	6	6	PIURA	SULLANA	MIGUEL CHECA	-5.19994729999999983	-80.633630299999993
1580	20	6	7	PIURA	SULLANA	QUERECOTILLO	-5.19994729999999983	-80.633630299999993
1581	20	6	8	PIURA	SULLANA	SALITRAL	-5.19994729999999983	-80.633630299999993
1582	20	7	1	PIURA	TALARA	PARIÑAS	-5.19994729999999983	-80.633630299999993
1583	20	7	2	PIURA	TALARA	EL ALTO	-5.19994729999999983	-80.633630299999993
1585	20	7	4	PIURA	TALARA	LOBITOS	-5.19994729999999983	-80.633630299999993
1586	20	7	5	PIURA	TALARA	LOS ORGANOS	-5.19994729999999983	-80.633630299999993
1587	20	7	6	PIURA	TALARA	MANCORA	-5.19994729999999983	-80.633630299999993
1588	20	8	1	PIURA	SECHURA	SECHURA	-5.19994729999999983	-80.633630299999993
1589	20	8	2	PIURA	SECHURA	BELLAVISTA DE LA UNION	-5.19994729999999983	-80.633630299999993
1590	20	8	3	PIURA	SECHURA	BERNAL	-5.19994729999999983	-80.633630299999993
1591	20	8	4	PIURA	SECHURA	CRISTO NOS VALGA	-5.19994729999999983	-80.633630299999993
1592	20	8	5	PIURA	SECHURA	VICE	-5.19994729999999983	-80.633630299999993
1593	20	8	6	PIURA	SECHURA	RINCONADA LLICUAR	-5.19994729999999983	-80.633630299999993
370	4	3	3	AREQUIPA	CARAVELI	ATICO	-16.3990141999999999	-71.5363660999999951
371	4	3	4	AREQUIPA	CARAVELI	ATIQUIPA	-16.3990141999999999	-71.5363660999999951
372	4	3	5	AREQUIPA	CARAVELI	BELLA UNION	-16.3990141999999999	-71.5363660999999951
373	4	3	6	AREQUIPA	CARAVELI	CAHUACHO	-16.3990141999999999	-71.5363660999999951
374	4	3	7	AREQUIPA	CARAVELI	CHALA	-16.3990141999999999	-71.5363660999999951
375	4	3	8	AREQUIPA	CARAVELI	CHAPARRA	-16.3990141999999999	-71.5363660999999951
376	4	3	9	AREQUIPA	CARAVELI	HUANUHUANU	-16.3990141999999999	-71.5363660999999951
377	4	3	10	AREQUIPA	CARAVELI	JAQUI	-16.3990141999999999	-71.5363660999999951
378	4	3	11	AREQUIPA	CARAVELI	LOMAS	-16.3990141999999999	-71.5363660999999951
379	4	3	12	AREQUIPA	CARAVELI	QUICACHA	-16.3990141999999999	-71.5363660999999951
380	4	3	13	AREQUIPA	CARAVELI	YAUCA	-16.3990141999999999	-71.5363660999999951
381	4	4	1	AREQUIPA	CASTILLA	APLAO	-16.3990141999999999	-71.5363660999999951
382	4	4	2	AREQUIPA	CASTILLA	ANDAGUA	-16.3990141999999999	-71.5363660999999951
383	4	4	3	AREQUIPA	CASTILLA	AYO	-16.3990141999999999	-71.5363660999999951
384	4	4	4	AREQUIPA	CASTILLA	CHACHAS	-16.3990141999999999	-71.5363660999999951
385	4	4	5	AREQUIPA	CASTILLA	CHILCAYMARCA	-16.3990141999999999	-71.5363660999999951
386	4	4	6	AREQUIPA	CASTILLA	CHOCO	-16.3990141999999999	-71.5363660999999951
387	4	4	7	AREQUIPA	CASTILLA	HUANCARQUI	-16.3990141999999999	-71.5363660999999951
388	4	4	8	AREQUIPA	CASTILLA	MACHAGUAY	-16.3990141999999999	-71.5363660999999951
389	4	4	9	AREQUIPA	CASTILLA	ORCOPAMPA	-16.3990141999999999	-71.5363660999999951
390	4	4	10	AREQUIPA	CASTILLA	PAMPACOLCA	-16.3990141999999999	-71.5363660999999951
391	4	4	11	AREQUIPA	CASTILLA	TIPAN	-16.3990141999999999	-71.5363660999999951
392	4	4	12	AREQUIPA	CASTILLA	UÑON	-16.3990141999999999	-71.5363660999999951
393	4	4	13	AREQUIPA	CASTILLA	URACA	-16.3990141999999999	-71.5363660999999951
394	4	4	14	AREQUIPA	CASTILLA	VIRACO	-16.3990141999999999	-71.5363660999999951
395	4	5	1	AREQUIPA	CAYLLOMA	CHIVAY	-16.3990141999999999	-71.5363660999999951
396	4	5	2	AREQUIPA	CAYLLOMA	ACHOMA	-16.3990141999999999	-71.5363660999999951
397	4	5	3	AREQUIPA	CAYLLOMA	CABANACONDE	-16.3990141999999999	-71.5363660999999951
398	4	5	4	AREQUIPA	CAYLLOMA	CALLALLI	-16.3990141999999999	-71.5363660999999951
399	4	5	5	AREQUIPA	CAYLLOMA	CAYLLOMA	-16.3990141999999999	-71.5363660999999951
400	4	5	6	AREQUIPA	CAYLLOMA	COPORAQUE	-16.3990141999999999	-71.5363660999999951
401	4	5	7	AREQUIPA	CAYLLOMA	HUAMBO	-16.3990141999999999	-71.5363660999999951
402	4	5	8	AREQUIPA	CAYLLOMA	HUANCA	-16.3990141999999999	-71.5363660999999951
403	4	5	9	AREQUIPA	CAYLLOMA	ICHUPAMPA	-16.3990141999999999	-71.5363660999999951
404	4	5	10	AREQUIPA	CAYLLOMA	LARI	-16.3990141999999999	-71.5363660999999951
405	4	5	11	AREQUIPA	CAYLLOMA	LLUTA	-16.3990141999999999	-71.5363660999999951
406	4	5	12	AREQUIPA	CAYLLOMA	MACA	-16.3990141999999999	-71.5363660999999951
407	4	5	13	AREQUIPA	CAYLLOMA	MADRIGAL	-16.3990141999999999	-71.5363660999999951
408	4	5	14	AREQUIPA	CAYLLOMA	SAN ANTONIO DE CHUCA	-16.3990141999999999	-71.5363660999999951
409	4	5	15	AREQUIPA	CAYLLOMA	SIBAYO	-16.3990141999999999	-71.5363660999999951
410	4	5	16	AREQUIPA	CAYLLOMA	TAPAY	-16.3990141999999999	-71.5363660999999951
411	4	5	17	AREQUIPA	CAYLLOMA	TISCO	-16.3990141999999999	-71.5363660999999951
412	4	5	18	AREQUIPA	CAYLLOMA	TUTI	-16.3990141999999999	-71.5363660999999951
413	4	5	19	AREQUIPA	CAYLLOMA	YANQUE	-16.3990141999999999	-71.5363660999999951
414	4	5	20	AREQUIPA	CAYLLOMA	MAJES	-16.3990141999999999	-71.5363660999999951
415	4	6	1	AREQUIPA	CONDESUYOS	CHUQUIBAMBA	-16.3990141999999999	-71.5363660999999951
416	4	6	2	AREQUIPA	CONDESUYOS	ANDARAY	-16.3990141999999999	-71.5363660999999951
417	4	6	3	AREQUIPA	CONDESUYOS	CAYARANI	-16.3990141999999999	-71.5363660999999951
418	4	6	4	AREQUIPA	CONDESUYOS	CHICHAS	-16.3990141999999999	-71.5363660999999951
419	4	6	5	AREQUIPA	CONDESUYOS	IRAY	-16.3990141999999999	-71.5363660999999951
420	4	6	6	AREQUIPA	CONDESUYOS	RIO GRANDE	-16.3990141999999999	-71.5363660999999951
421	4	6	7	AREQUIPA	CONDESUYOS	SALAMANCA	-16.3990141999999999	-71.5363660999999951
422	4	6	8	AREQUIPA	CONDESUYOS	YANAQUIHUA	-16.3990141999999999	-71.5363660999999951
423	4	7	1	AREQUIPA	ISLAY	MOLLENDO	-16.3990141999999999	-71.5363660999999951
424	4	7	2	AREQUIPA	ISLAY	COCACHACRA	-16.3990141999999999	-71.5363660999999951
425	4	7	3	AREQUIPA	ISLAY	DEAN VALDIVIA	-16.3990141999999999	-71.5363660999999951
426	4	7	4	AREQUIPA	ISLAY	ISLAY	-16.3990141999999999	-71.5363660999999951
427	4	7	5	AREQUIPA	ISLAY	MEJIA	-16.3990141999999999	-71.5363660999999951
428	4	7	6	AREQUIPA	ISLAY	PUNTA DE BOMBON	-16.3990141999999999	-71.5363660999999951
429	4	8	1	AREQUIPA	LA UNION	COTAHUASI	-16.3990141999999999	-71.5363660999999951
430	4	8	2	AREQUIPA	LA UNION	ALCA	-16.3990141999999999	-71.5363660999999951
431	4	8	3	AREQUIPA	LA UNION	CHARCANA	-16.3990141999999999	-71.5363660999999951
432	4	8	4	AREQUIPA	LA UNION	HUAYNACOTAS	-16.3990141999999999	-71.5363660999999951
433	4	8	5	AREQUIPA	LA UNION	PAMPAMARCA	-16.3990141999999999	-71.5363660999999951
434	4	8	6	AREQUIPA	LA UNION	PUYCA	-16.3990141999999999	-71.5363660999999951
435	4	8	7	AREQUIPA	LA UNION	QUECHUALLA	-16.3990141999999999	-71.5363660999999951
436	4	8	8	AREQUIPA	LA UNION	SAYLA	-16.3990141999999999	-71.5363660999999951
437	4	8	9	AREQUIPA	LA UNION	TAURIA	-16.3990141999999999	-71.5363660999999951
438	4	8	10	AREQUIPA	LA UNION	TOMEPAMPA	-16.3990141999999999	-71.5363660999999951
439	4	8	11	AREQUIPA	LA UNION	TORO	-16.3990141999999999	-71.5363660999999951
\.


--
-- TOC entry 3032 (class 0 OID 0)
-- Dependencies: 279
-- Name: ubigeo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('ubigeo_id_seq', 1833, true);


--
-- TOC entry 2851 (class 0 OID 16849)
-- Dependencies: 280
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY usuario (id, nombre, password, salt, id_persona) FROM stdin;
1	marks	k2EkSjl4U2/3QjcEGm56sZEvBQAk+9CvgccRR+u7yFmbhXVdDMKP7vaQ1pw49hNe04nQz6VzC36U0PQUw+4NvQ==	ecf5b3bf74815835c6c9442fcc427580	\N
27	macn	zXsfKoQr6lxGjIqlVMs6BjwO2zwQfAMLR97L35fN5EBWRFuwSBdS8Qh5ki0Aa9xITHXUt6ycd0slcGdyM9i22A==	c70469254b1f2e96a873c58f84f87eb5	81
\.


--
-- TOC entry 3033 (class 0 OID 0)
-- Dependencies: 281
-- Name: usuario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('usuario_id_seq', 27, true);


--
-- TOC entry 2560 (class 2606 OID 16888)
-- Name: asistencia_clase_curso_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY asistencia_clase_curso
    ADD CONSTRAINT asistencia_clase_curso_pk PRIMARY KEY (id_persona_estudiante, id_clase_curso);


--
-- TOC entry 2567 (class 2606 OID 16890)
-- Name: clase_celula; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY clase_cell
    ADD CONSTRAINT clase_celula PRIMARY KEY (id);


--
-- TOC entry 2572 (class 2606 OID 16892)
-- Name: consolidacion; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY consolida
    ADD CONSTRAINT consolidacion PRIMARY KEY (id);


--
-- TOC entry 2582 (class 2606 OID 16894)
-- Name: consolidador_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY consolidador
    ADD CONSTRAINT consolidador_pk PRIMARY KEY (id);


--
-- TOC entry 2589 (class 2606 OID 16896)
-- Name: curso_dictado; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY curso_impartido
    ADD CONSTRAINT curso_dictado PRIMARY KEY (id);


--
-- TOC entry 2592 (class 2606 OID 16898)
-- Name: descartado_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY descartado
    ADD CONSTRAINT descartado_pk PRIMARY KEY (id);


--
-- TOC entry 2595 (class 2606 OID 16900)
-- Name: docente_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY docente
    ADD CONSTRAINT docente_pk PRIMARY KEY (id_persona);


--
-- TOC entry 2598 (class 2606 OID 16902)
-- Name: encargado_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY encargado
    ADD CONSTRAINT encargado_pk PRIMARY KEY (id);


--
-- TOC entry 2601 (class 2606 OID 16904)
-- Name: estudiante_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY estudiante
    ADD CONSTRAINT estudiante_pk PRIMARY KEY (id);


--
-- TOC entry 2604 (class 2606 OID 16906)
-- Name: evaluacion_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY evaluacion
    ADD CONSTRAINT evaluacion_pk PRIMARY KEY (id_persona_estudiante, id_criterio_evaluacion);


--
-- TOC entry 2657 (class 2606 OID 16908)
-- Name: ids; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY local
    ADD CONSTRAINT ids PRIMARY KEY (id);


--
-- TOC entry 2620 (class 2606 OID 16910)
-- Name: iglesia_area_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY iglesia_area
    ADD CONSTRAINT iglesia_area_pk PRIMARY KEY (id_iglesia, id_area_vision);


--
-- TOC entry 2627 (class 2606 OID 16912)
-- Name: lider_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY lider
    ADD CONSTRAINT lider_pk PRIMARY KEY (id);


--
-- TOC entry 2630 (class 2606 OID 16914)
-- Name: lider_red_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY lider_red
    ADD CONSTRAINT lider_red_pk PRIMARY KEY (id);


--
-- TOC entry 2636 (class 2606 OID 16916)
-- Name: lider_red_uq; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY red
    ADD CONSTRAINT lider_red_uq UNIQUE (id_lider_red);


--
-- TOC entry 2663 (class 2606 OID 16918)
-- Name: many_clase_celula_has_many_miembro_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY many_clase_celula_has_many_miembro
    ADD CONSTRAINT many_clase_celula_has_many_miembro_pk PRIMARY KEY (id_clase_cell, id_miembro);


--
-- TOC entry 2665 (class 2606 OID 16920)
-- Name: many_consolidacion_has_many_tema_leche_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY many_consolidacion_has_many_tema_leche
    ADD CONSTRAINT many_consolidacion_has_many_tema_leche_pk PRIMARY KEY (id_consolida, id_tema_leche);


--
-- TOC entry 2667 (class 2606 OID 16922)
-- Name: many_encargado_has_many_area_vision_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY many_encargado_has_many_area_vision
    ADD CONSTRAINT many_encargado_has_many_area_vision_pk PRIMARY KEY (id_encargado, id_area_vision);


--
-- TOC entry 2671 (class 2606 OID 16924)
-- Name: many_herramienta_has_many_consolidacion_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY many_herramienta_has_many_consolidacion
    ADD CONSTRAINT many_herramienta_has_many_consolidacion_pk PRIMARY KEY (id_herramienta, id_consolida);


--
-- TOC entry 2673 (class 2606 OID 16926)
-- Name: many_usuario_has_many_rol_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY many_usuario_has_many_rol
    ADD CONSTRAINT many_usuario_has_many_rol_pk PRIMARY KEY (id_usuario, id_rol);


--
-- TOC entry 2675 (class 2606 OID 16928)
-- Name: matriculas; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY matric
    ADD CONSTRAINT matriculas PRIMARY KEY (id);


--
-- TOC entry 2577 (class 2606 OID 16930)
-- Name: miembro_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY miembro
    ADD CONSTRAINT miembro_pk PRIMARY KEY (id);


--
-- TOC entry 2643 (class 2606 OID 16932)
-- Name: misionero_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY misionero
    ADD CONSTRAINT misionero_pk PRIMARY KEY (id);


--
-- TOC entry 2678 (class 2606 OID 16934)
-- Name: nuevo_convertido_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY nuevo_convertido
    ADD CONSTRAINT nuevo_convertido_pk PRIMARY KEY (id);


--
-- TOC entry 2646 (class 2606 OID 16936)
-- Name: pastor_asociado_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY pastor_asociado
    ADD CONSTRAINT pastor_asociado_pk PRIMARY KEY (id);


--
-- TOC entry 2638 (class 2606 OID 16938)
-- Name: pastor_asociado_uq; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY red
    ADD CONSTRAINT pastor_asociado_uq UNIQUE (id_pastor_asociado);


--
-- TOC entry 2649 (class 2606 OID 16940)
-- Name: pastor_ejecutivo_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY pastor_ejecutivo
    ADD CONSTRAINT pastor_ejecutivo_pk PRIMARY KEY (id);


--
-- TOC entry 2692 (class 2606 OID 16942)
-- Name: persona_uq; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT persona_uq UNIQUE (id_persona);


--
-- TOC entry 2556 (class 2606 OID 16944)
-- Name: pk_archivo; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY archivo
    ADD CONSTRAINT pk_archivo PRIMARY KEY (id);


--
-- TOC entry 2558 (class 2606 OID 16946)
-- Name: pk_area_vision; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY area_vision
    ADD CONSTRAINT pk_area_vision PRIMARY KEY (id);


--
-- TOC entry 2565 (class 2606 OID 16948)
-- Name: pk_celula; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY celula
    ADD CONSTRAINT pk_celula PRIMARY KEY (id);


--
-- TOC entry 2570 (class 2606 OID 16950)
-- Name: pk_clase_curso; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY clase_curso
    ADD CONSTRAINT pk_clase_curso PRIMARY KEY (id);


--
-- TOC entry 2584 (class 2606 OID 16952)
-- Name: pk_criterio_evaluacion; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY criterio_evaluacion
    ADD CONSTRAINT pk_criterio_evaluacion PRIMARY KEY (id);


--
-- TOC entry 2587 (class 2606 OID 16954)
-- Name: pk_curso; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY curso
    ADD CONSTRAINT pk_curso PRIMARY KEY (id);


--
-- TOC entry 2652 (class 2606 OID 16956)
-- Name: pk_division_politica; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ubigeo
    ADD CONSTRAINT pk_division_politica PRIMARY KEY (id);


--
-- TOC entry 2607 (class 2606 OID 16958)
-- Name: pk_evento; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY evento
    ADD CONSTRAINT pk_evento PRIMARY KEY (id);


--
-- TOC entry 2609 (class 2606 OID 16960)
-- Name: pk_evento_realizado; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY evento_realizado
    ADD CONSTRAINT pk_evento_realizado PRIMARY KEY (id);


--
-- TOC entry 2612 (class 2606 OID 16962)
-- Name: pk_herramienta; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY herramienta
    ADD CONSTRAINT pk_herramienta PRIMARY KEY (id);


--
-- TOC entry 2614 (class 2606 OID 16964)
-- Name: pk_horario; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY horario
    ADD CONSTRAINT pk_horario PRIMARY KEY (id);


--
-- TOC entry 2616 (class 2606 OID 16966)
-- Name: pk_iglesia; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY iglesia
    ADD CONSTRAINT pk_iglesia PRIMARY KEY (id);


--
-- TOC entry 2622 (class 2606 OID 16968)
-- Name: pk_informe; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY informe
    ADD CONSTRAINT pk_informe PRIMARY KEY (id);


--
-- TOC entry 2624 (class 2606 OID 16970)
-- Name: pk_leche_espiritual; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY leche_espiritual
    ADD CONSTRAINT pk_leche_espiritual PRIMARY KEY (id);


--
-- TOC entry 2659 (class 2606 OID 16972)
-- Name: pk_lugar; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY lugar
    ADD CONSTRAINT pk_lugar PRIMARY KEY (id);


--
-- TOC entry 2580 (class 2606 OID 16974)
-- Name: pk_persona; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY persona
    ADD CONSTRAINT pk_persona PRIMARY KEY (id);


--
-- TOC entry 2680 (class 2606 OID 16976)
-- Name: pk_prerequisito; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY prerequisito
    ADD CONSTRAINT pk_prerequisito PRIMARY KEY (id_curso);


--
-- TOC entry 2640 (class 2606 OID 16978)
-- Name: pk_red; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY red
    ADD CONSTRAINT pk_red PRIMARY KEY (id);


--
-- TOC entry 2682 (class 2606 OID 16980)
-- Name: pk_red_social; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY red_social
    ADD CONSTRAINT pk_red_social PRIMARY KEY (id);


--
-- TOC entry 2684 (class 2606 OID 16982)
-- Name: pk_rol; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY rol
    ADD CONSTRAINT pk_rol PRIMARY KEY (id);


--
-- TOC entry 2655 (class 2606 OID 16984)
-- Name: pk_tema_celula; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tema_celula
    ADD CONSTRAINT pk_tema_celula PRIMARY KEY (id);


--
-- TOC entry 2686 (class 2606 OID 16986)
-- Name: pk_tema_curso; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tema_curso
    ADD CONSTRAINT pk_tema_curso PRIMARY KEY (id);


--
-- TOC entry 2688 (class 2606 OID 16988)
-- Name: pk_tema_leche; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tema_leche
    ADD CONSTRAINT pk_tema_leche PRIMARY KEY (id);


--
-- TOC entry 2633 (class 2606 OID 16990)
-- Name: pk_ubicación; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ubicacion
    ADD CONSTRAINT "pk_ubicación" PRIMARY KEY (id);


--
-- TOC entry 2694 (class 2606 OID 16992)
-- Name: pk_usuario; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT pk_usuario PRIMARY KEY (id);


--
-- TOC entry 2618 (class 2606 OID 16994)
-- Name: ubicacion_uq; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY iglesia
    ADD CONSTRAINT ubicacion_uq UNIQUE (id_ubicacion);


--
-- TOC entry 2696 (class 2606 OID 16996)
-- Name: uq_nombre; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT uq_nombre UNIQUE (nombre);


--
-- TOC entry 2668 (class 1259 OID 16997)
-- Name: consolida_id; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX consolida_id ON many_herramienta_has_many_consolidacion USING btree (id_consolida);


--
-- TOC entry 2650 (class 1259 OID 16998)
-- Name: departamento; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX departamento ON ubigeo USING btree (coddepartamento);


--
-- TOC entry 2590 (class 1259 OID 16999)
-- Name: fki_id_local_fk; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX fki_id_local_fk ON curso_impartido USING btree (id_local);


--
-- TOC entry 2561 (class 1259 OID 19041)
-- Name: fki_lider_fk; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX fki_lider_fk ON celula USING btree (id_lider);


--
-- TOC entry 2562 (class 1259 OID 19035)
-- Name: fki_lider_red_celula_fk; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX fki_lider_red_celula_fk ON celula USING btree (id_lider_red);


--
-- TOC entry 2605 (class 1259 OID 17000)
-- Name: fki_ubicacion; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX fki_ubicacion ON evento USING btree (id_ubicacion);


--
-- TOC entry 2669 (class 1259 OID 17001)
-- Name: herramienta_id; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX herramienta_id ON many_herramienta_has_many_consolidacion USING btree (id_herramienta);


--
-- TOC entry 2634 (class 1259 OID 17002)
-- Name: id; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX id ON red USING btree (id);


--
-- TOC entry 2676 (class 1259 OID 17003)
-- Name: idconvertido; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idconvertido ON nuevo_convertido USING btree (id);


--
-- TOC entry 2593 (class 1259 OID 17004)
-- Name: iddescarte; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX iddescarte ON descartado USING btree (id);


--
-- TOC entry 2602 (class 1259 OID 17005)
-- Name: idestudiante; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idestudiante ON estudiante USING btree (id);


--
-- TOC entry 2625 (class 1259 OID 17006)
-- Name: idlider; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idlider ON lider USING btree (id);


--
-- TOC entry 2628 (class 1259 OID 17007)
-- Name: idlider_red; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idlider_red ON lider_red USING btree (id);


--
-- TOC entry 2575 (class 1259 OID 17008)
-- Name: idmiembro; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idmiembro ON miembro USING btree (id);


--
-- TOC entry 2641 (class 1259 OID 17009)
-- Name: idmisionero; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idmisionero ON misionero USING btree (id);


--
-- TOC entry 2644 (class 1259 OID 17010)
-- Name: idpastor_asociado; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idpastor_asociado ON pastor_asociado USING btree (id);


--
-- TOC entry 2647 (class 1259 OID 17011)
-- Name: idpastor_ejecutivo; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idpastor_ejecutivo ON pastor_ejecutivo USING btree (id);


--
-- TOC entry 2578 (class 1259 OID 17012)
-- Name: idpersona; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idpersona ON persona USING btree (id);


--
-- TOC entry 2631 (class 1259 OID 17013)
-- Name: idubicacion; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idubicacion ON ubicacion USING btree (id);


--
-- TOC entry 2573 (class 1259 OID 19248)
-- Name: idxid; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idxid ON consolida USING btree (id);


--
-- TOC entry 2568 (class 1259 OID 17014)
-- Name: ind; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX ind ON clase_cell USING btree (id);


--
-- TOC entry 2660 (class 1259 OID 17015)
-- Name: indclase_celula; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX indclase_celula ON many_clase_celula_has_many_miembro USING btree (id_clase_cell);


--
-- TOC entry 2585 (class 1259 OID 17016)
-- Name: indcurso; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX indcurso ON curso USING btree (id);


--
-- TOC entry 2596 (class 1259 OID 17017)
-- Name: inddocente; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX inddocente ON docente USING btree (id_persona);


--
-- TOC entry 2599 (class 1259 OID 17018)
-- Name: indencargado; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX indencargado ON encargado USING btree (id);


--
-- TOC entry 2689 (class 1259 OID 17019)
-- Name: index_nombre; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX index_nombre ON usuario USING btree (nombre DESC NULLS LAST) WITH (fillfactor=10);


--
-- TOC entry 2690 (class 1259 OID 18463)
-- Name: index_user_id; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX index_user_id ON usuario USING btree (id);


--
-- TOC entry 2552 (class 1259 OID 19232)
-- Name: indid; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX indid ON archivo USING btree (id);


--
-- TOC entry 2553 (class 1259 OID 19234)
-- Name: indireccion; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX indireccion ON archivo USING btree (direccion);


--
-- TOC entry 2661 (class 1259 OID 17020)
-- Name: indmiembro; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX indmiembro ON many_clase_celula_has_many_miembro USING btree (id_miembro);


--
-- TOC entry 2554 (class 1259 OID 19233)
-- Name: indnombre; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX indnombre ON archivo USING btree (nombre);


--
-- TOC entry 2574 (class 1259 OID 17021)
-- Name: indoconsolidador; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX indoconsolidador ON consolida USING btree (id_consolidador);


--
-- TOC entry 2563 (class 1259 OID 17022)
-- Name: indred; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX indred ON celula USING btree (id_red);


--
-- TOC entry 2610 (class 1259 OID 17023)
-- Name: nombre_herramienta; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX nombre_herramienta ON herramienta USING btree (nombre);


--
-- TOC entry 2653 (class 1259 OID 17024)
-- Name: provincia; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX provincia ON ubigeo USING btree (codprovincia);


--
-- TOC entry 2736 (class 2606 OID 17025)
-- Name: area_vision_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY iglesia_area
    ADD CONSTRAINT area_vision_fk FOREIGN KEY (id_area_vision) REFERENCES area_vision(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2758 (class 2606 OID 17030)
-- Name: area_vision_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY many_encargado_has_many_area_vision
    ADD CONSTRAINT area_vision_fk FOREIGN KEY (id_area_vision) REFERENCES area_vision(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2715 (class 2606 OID 17035)
-- Name: celula_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY miembro
    ADD CONSTRAINT celula_fk FOREIGN KEY (id_celula) REFERENCES celula(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2766 (class 2606 OID 17040)
-- Name: celula_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY nuevo_convertido
    ADD CONSTRAINT celula_fk FOREIGN KEY (id_celula) REFERENCES celula(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2708 (class 2606 OID 17045)
-- Name: celula_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY clase_cell
    ADD CONSTRAINT celula_fk FOREIGN KEY (id_celula) REFERENCES celula(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2754 (class 2606 OID 17050)
-- Name: clase_cell_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY many_clase_celula_has_many_miembro
    ADD CONSTRAINT clase_cell_fk FOREIGN KEY (id_clase_cell) REFERENCES clase_cell(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2700 (class 2606 OID 17055)
-- Name: clase_curso_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY asistencia_clase_curso
    ADD CONSTRAINT clase_curso_fk FOREIGN KEY (id_clase_curso) REFERENCES clase_curso(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2760 (class 2606 OID 17060)
-- Name: consolida_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY many_herramienta_has_many_consolidacion
    ADD CONSTRAINT consolida_fk FOREIGN KEY (id_consolida) REFERENCES consolida(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2756 (class 2606 OID 17065)
-- Name: consolida_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY many_consolidacion_has_many_tema_leche
    ADD CONSTRAINT consolida_fk FOREIGN KEY (id_consolida) REFERENCES consolida(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2712 (class 2606 OID 17070)
-- Name: consolidador_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY consolida
    ADD CONSTRAINT consolidador_fk FOREIGN KEY (id_consolidador) REFERENCES consolidador(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2730 (class 2606 OID 17075)
-- Name: criterio_evaluacion_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evaluacion
    ADD CONSTRAINT criterio_evaluacion_fk FOREIGN KEY (id_criterio_evaluacion) REFERENCES criterio_evaluacion(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2772 (class 2606 OID 17080)
-- Name: curso_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tema_curso
    ADD CONSTRAINT curso_fk FOREIGN KEY (id_curso) REFERENCES curso(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2770 (class 2606 OID 17085)
-- Name: curso_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY prerequisito
    ADD CONSTRAINT curso_fk FOREIGN KEY (id_curso1) REFERENCES curso(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2722 (class 2606 OID 17090)
-- Name: curso_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY curso_impartido
    ADD CONSTRAINT curso_fk FOREIGN KEY (id_curso) REFERENCES curso(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2721 (class 2606 OID 17095)
-- Name: curso_impartido_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY criterio_evaluacion
    ADD CONSTRAINT curso_impartido_fk FOREIGN KEY (id_curso_impartido) REFERENCES curso_impartido(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2764 (class 2606 OID 17100)
-- Name: curso_impartido_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY matric
    ADD CONSTRAINT curso_impartido_fk FOREIGN KEY (id_curso_impartido) REFERENCES curso_impartido(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2711 (class 2606 OID 17105)
-- Name: curso_impartido_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY clase_curso
    ADD CONSTRAINT curso_impartido_fk FOREIGN KEY (id_curso_impartido) REFERENCES curso_impartido(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2723 (class 2606 OID 17110)
-- Name: docente_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY curso_impartido
    ADD CONSTRAINT docente_fk FOREIGN KEY (id_persona_docente) REFERENCES docente(id_persona) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2738 (class 2606 OID 17115)
-- Name: encargado_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY informe
    ADD CONSTRAINT encargado_fk FOREIGN KEY (id_encargado) REFERENCES encargado(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2759 (class 2606 OID 17120)
-- Name: encargado_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY many_encargado_has_many_area_vision
    ADD CONSTRAINT encargado_fk FOREIGN KEY (id_encargado) REFERENCES encargado(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2731 (class 2606 OID 17125)
-- Name: estudiante_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evaluacion
    ADD CONSTRAINT estudiante_fk FOREIGN KEY (id_persona_estudiante) REFERENCES estudiante(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2701 (class 2606 OID 17130)
-- Name: estudiante_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY asistencia_clase_curso
    ADD CONSTRAINT estudiante_fk FOREIGN KEY (id_persona_estudiante) REFERENCES estudiante(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2765 (class 2606 OID 17135)
-- Name: estudiante_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY matric
    ADD CONSTRAINT estudiante_fk FOREIGN KEY (id_persona_estudiante) REFERENCES estudiante(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2733 (class 2606 OID 17140)
-- Name: evento_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_realizado
    ADD CONSTRAINT evento_fk FOREIGN KEY (id_evento) REFERENCES evento(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2761 (class 2606 OID 17145)
-- Name: herramienta_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY many_herramienta_has_many_consolidacion
    ADD CONSTRAINT herramienta_fk FOREIGN KEY (id_herramienta) REFERENCES herramienta(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2709 (class 2606 OID 17150)
-- Name: horario_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY clase_cell
    ADD CONSTRAINT horario_fk FOREIGN KEY (id_horario) REFERENCES horario(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2724 (class 2606 OID 17155)
-- Name: horario_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY curso_impartido
    ADD CONSTRAINT horario_fk FOREIGN KEY (id_horario) REFERENCES horario(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2725 (class 2606 OID 17160)
-- Name: id_local_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY curso_impartido
    ADD CONSTRAINT id_local_fk FOREIGN KEY (id_local) REFERENCES local(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- TOC entry 2718 (class 2606 OID 17165)
-- Name: iglesia_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY persona
    ADD CONSTRAINT iglesia_fk FOREIGN KEY (id_iglesia) REFERENCES iglesia(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2747 (class 2606 OID 17170)
-- Name: iglesia_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY red
    ADD CONSTRAINT iglesia_fk FOREIGN KEY (id_iglesia) REFERENCES iglesia(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2737 (class 2606 OID 17175)
-- Name: iglesia_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY iglesia_area
    ADD CONSTRAINT iglesia_fk FOREIGN KEY (id_iglesia) REFERENCES iglesia(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2773 (class 2606 OID 17180)
-- Name: leche_espiritual_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tema_leche
    ADD CONSTRAINT leche_espiritual_fk FOREIGN KEY (id_leche_espiritual) REFERENCES leche_espiritual(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2697 (class 2606 OID 17185)
-- Name: leche_espiritual_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY archivo
    ADD CONSTRAINT leche_espiritual_fk FOREIGN KEY (id_leche_espiritual) REFERENCES leche_espiritual(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2739 (class 2606 OID 17190)
-- Name: lider_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY informe
    ADD CONSTRAINT lider_fk FOREIGN KEY (id_lider) REFERENCES lider(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2707 (class 2606 OID 19042)
-- Name: lider_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY celula
    ADD CONSTRAINT lider_fk FOREIGN KEY (id_lider) REFERENCES lider(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2706 (class 2606 OID 19030)
-- Name: lider_red_celula_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY celula
    ADD CONSTRAINT lider_red_celula_fk FOREIGN KEY (id_lider_red) REFERENCES lider_red(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2740 (class 2606 OID 17195)
-- Name: lider_red_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY informe
    ADD CONSTRAINT lider_red_fk FOREIGN KEY (id_lider_red) REFERENCES lider_red(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2748 (class 2606 OID 17200)
-- Name: lider_red_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY red
    ADD CONSTRAINT lider_red_fk FOREIGN KEY (id_lider_red) REFERENCES lider_red(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2767 (class 2606 OID 17205)
-- Name: lugar_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY nuevo_convertido
    ADD CONSTRAINT lugar_fk FOREIGN KEY (id_lugar) REFERENCES lugar(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2713 (class 2606 OID 17210)
-- Name: miembro_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY consolida
    ADD CONSTRAINT miembro_fk FOREIGN KEY (id_miembro) REFERENCES miembro(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2755 (class 2606 OID 17215)
-- Name: miembro_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY many_clase_celula_has_many_miembro
    ADD CONSTRAINT miembro_fk FOREIGN KEY (id_miembro) REFERENCES miembro(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2741 (class 2606 OID 17220)
-- Name: misionero_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY informe
    ADD CONSTRAINT misionero_fk FOREIGN KEY (id_misionero) REFERENCES misionero(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2702 (class 2606 OID 17225)
-- Name: misionero_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY celula
    ADD CONSTRAINT misionero_fk FOREIGN KEY (id_misionero) REFERENCES misionero(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2714 (class 2606 OID 17230)
-- Name: nuevo_convertido_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY consolida
    ADD CONSTRAINT nuevo_convertido_fk FOREIGN KEY (id_nuevo_convertido) REFERENCES nuevo_convertido(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2742 (class 2606 OID 17235)
-- Name: pastor_asociado_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY informe
    ADD CONSTRAINT pastor_asociado_fk FOREIGN KEY (id_pastor_asociado) REFERENCES pastor_asociado(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2749 (class 2606 OID 17240)
-- Name: pastor_asociado_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY red
    ADD CONSTRAINT pastor_asociado_fk FOREIGN KEY (id_pastor_asociado) REFERENCES pastor_asociado(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2743 (class 2606 OID 17245)
-- Name: pastor_ejecutivo_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY informe
    ADD CONSTRAINT pastor_ejecutivo_fk FOREIGN KEY (id_pastor_ejecutivo) REFERENCES pastor_ejecutivo(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2703 (class 2606 OID 17250)
-- Name: pastor_ejecutivo_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY celula
    ADD CONSTRAINT pastor_ejecutivo_fk FOREIGN KEY (id_pastor_ejecutivo) REFERENCES pastor_ejecutivo(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2774 (class 2606 OID 17255)
-- Name: persona_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT persona_fk FOREIGN KEY (id_persona) REFERENCES persona(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2771 (class 2606 OID 17260)
-- Name: persona_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY red_social
    ADD CONSTRAINT persona_fk FOREIGN KEY (id_persona) REFERENCES persona(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2726 (class 2606 OID 17265)
-- Name: persona_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY descartado
    ADD CONSTRAINT persona_fk FOREIGN KEY (id) REFERENCES persona(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2744 (class 2606 OID 17270)
-- Name: persona_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY lider
    ADD CONSTRAINT persona_fk FOREIGN KEY (id) REFERENCES persona(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2745 (class 2606 OID 17275)
-- Name: persona_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY lider_red
    ADD CONSTRAINT persona_fk FOREIGN KEY (id) REFERENCES persona(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2720 (class 2606 OID 17280)
-- Name: persona_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY consolidador
    ADD CONSTRAINT persona_fk FOREIGN KEY (id) REFERENCES persona(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2752 (class 2606 OID 17285)
-- Name: persona_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pastor_asociado
    ADD CONSTRAINT persona_fk FOREIGN KEY (id) REFERENCES persona(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2751 (class 2606 OID 17290)
-- Name: persona_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY misionero
    ADD CONSTRAINT persona_fk FOREIGN KEY (id) REFERENCES persona(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2728 (class 2606 OID 17295)
-- Name: persona_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY encargado
    ADD CONSTRAINT persona_fk FOREIGN KEY (id) REFERENCES persona(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2753 (class 2606 OID 17300)
-- Name: persona_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pastor_ejecutivo
    ADD CONSTRAINT persona_fk FOREIGN KEY (id) REFERENCES persona(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2768 (class 2606 OID 17305)
-- Name: persona_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY nuevo_convertido
    ADD CONSTRAINT persona_fk FOREIGN KEY (id) REFERENCES persona(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2716 (class 2606 OID 17310)
-- Name: persona_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY miembro
    ADD CONSTRAINT persona_fk FOREIGN KEY (id) REFERENCES persona(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2727 (class 2606 OID 17315)
-- Name: persona_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY docente
    ADD CONSTRAINT persona_fk FOREIGN KEY (id_persona) REFERENCES persona(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2729 (class 2606 OID 17320)
-- Name: persona_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY estudiante
    ADD CONSTRAINT persona_fk FOREIGN KEY (id) REFERENCES persona(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2734 (class 2606 OID 17325)
-- Name: persona_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento_realizado
    ADD CONSTRAINT persona_fk FOREIGN KEY (id_persona) REFERENCES persona(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2769 (class 2606 OID 17330)
-- Name: red_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY nuevo_convertido
    ADD CONSTRAINT red_fk FOREIGN KEY (id_red) REFERENCES red(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2717 (class 2606 OID 17335)
-- Name: red_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY miembro
    ADD CONSTRAINT red_fk FOREIGN KEY (id_red) REFERENCES red(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2704 (class 2606 OID 17340)
-- Name: red_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY celula
    ADD CONSTRAINT red_fk FOREIGN KEY (id_red) REFERENCES red(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2762 (class 2606 OID 17345)
-- Name: rol_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY many_usuario_has_many_rol
    ADD CONSTRAINT rol_fk FOREIGN KEY (id_rol) REFERENCES rol(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2710 (class 2606 OID 17350)
-- Name: tema_celula_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY clase_cell
    ADD CONSTRAINT tema_celula_fk FOREIGN KEY (id_tema_celula) REFERENCES tema_celula(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2698 (class 2606 OID 17355)
-- Name: tema_celula_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY archivo
    ADD CONSTRAINT tema_celula_fk FOREIGN KEY (id_tema_celula) REFERENCES tema_celula(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2699 (class 2606 OID 17360)
-- Name: tema_curso_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY archivo
    ADD CONSTRAINT tema_curso_fk FOREIGN KEY (id_tema_curso) REFERENCES tema_curso(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2757 (class 2606 OID 17365)
-- Name: tema_leche_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY many_consolidacion_has_many_tema_leche
    ADD CONSTRAINT tema_leche_fk FOREIGN KEY (id_tema_leche) REFERENCES tema_leche(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2732 (class 2606 OID 17370)
-- Name: ubicacion; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY evento
    ADD CONSTRAINT ubicacion FOREIGN KEY (id_ubicacion) REFERENCES ubicacion(id);


--
-- TOC entry 2750 (class 2606 OID 17380)
-- Name: ubicacion_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY red
    ADD CONSTRAINT ubicacion_fk FOREIGN KEY (id_ubicacion) REFERENCES ubicacion(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2705 (class 2606 OID 17385)
-- Name: ubicacion_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY celula
    ADD CONSTRAINT ubicacion_fk FOREIGN KEY (id_ubicacion) REFERENCES ubicacion(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2735 (class 2606 OID 17390)
-- Name: ubicacion_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY iglesia
    ADD CONSTRAINT ubicacion_fk FOREIGN KEY (id_ubicacion) REFERENCES ubicacion(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2719 (class 2606 OID 18466)
-- Name: ubicacion_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY persona
    ADD CONSTRAINT ubicacion_fk FOREIGN KEY (id_ubicacion) REFERENCES ubicacion(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2746 (class 2606 OID 17395)
-- Name: ubigeo_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ubicacion
    ADD CONSTRAINT ubigeo_fk FOREIGN KEY (id_ubigeo) REFERENCES ubigeo(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2763 (class 2606 OID 17400)
-- Name: usuario_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY many_usuario_has_many_rol
    ADD CONSTRAINT usuario_fk FOREIGN KEY (id_usuario) REFERENCES usuario(id) MATCH FULL ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 2859 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2013-05-15 00:42:09 PET

--
-- PostgreSQL database dump complete
--

