-- archivo

ALTER TABLE archivo DROP CONSTRAINT leche_espiritual_fk;

ALTER TABLE archivo
  ADD CONSTRAINT leche_espiritual_fk FOREIGN KEY (id_leche_espiritual)
      REFERENCES leche_espiritual (id) MATCH FULL
      ON UPDATE CASCADE ON DELETE SET NULL;
      
ALTER TABLE archivo DROP CONSTRAINT tema_celula_fk;

ALTER TABLE archivo
  ADD CONSTRAINT tema_celula_fk FOREIGN KEY (id_tema_celula)
      REFERENCES tema_celula (id) MATCH FULL
      ON UPDATE CASCADE ON DELETE SET NULL;
      
ALTER TABLE archivo DROP CONSTRAINT tema_curso_fk;

ALTER TABLE archivo
  ADD CONSTRAINT tema_curso_fk FOREIGN KEY (id_tema_curso)
      REFERENCES tema_curso (id) MATCH FULL
      ON UPDATE CASCADE ON DELETE SET NULL;



--celula
ALTER TABLE celula DROP CONSTRAINT lider_red_fk;

 ALTER TABLE celula DROP CONSTRAINT ubicacion_fk;

ALTER TABLE celula
  ADD CONSTRAINT ubicacion_fk FOREIGN KEY (id_ubicacion)
      REFERENCES ubicacion (id) MATCH FULL
      ON UPDATE CASCADE ON DELETE CASCADE;

--clase_cell


ALTER TABLE clase_cell DROP CONSTRAINT horario_fk;

ALTER TABLE clase_cell
  ADD CONSTRAINT horario_fk FOREIGN KEY (id_horario)
      REFERENCES horario (id) MATCH FULL
      ON UPDATE CASCADE ON DELETE CASCADE;


--curso impartido

ALTER TABLE curso_impartido DROP CONSTRAINT horario_fk;
ALTER TABLE curso_impartido
  ADD CONSTRAINT horario_fk FOREIGN KEY (id_horario)
      REFERENCES horario (id) MATCH FULL
      ON UPDATE CASCADE ON DELETE CASCADE;
       
 --evento realizado

ALTER TABLE evento_realizado DROP CONSTRAINT ubicacion_fk;

ALTER TABLE evento_realizado
  ADD CONSTRAINT ubicacion_fk FOREIGN KEY (id_ubicacion)
      REFERENCES ubicacion (id) MATCH FULL
      ON UPDATE CASCADE ON DELETE CASCADE;

--IGLESIA
ALTER TABLE iglesia DROP CONSTRAINT ubicacion_fk;

ALTER TABLE iglesia
  ADD CONSTRAINT ubicacion_fk FOREIGN KEY (id_ubicacion)
      REFERENCES ubicacion (id) MATCH FULL
      ON UPDATE CASCADE ON DELETE CASCADE;
