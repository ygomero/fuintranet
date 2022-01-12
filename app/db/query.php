<?php 

$querys = [
    "modulesSelectAll"  => "SELECT * FROM FU_MODULES",
    "usersSelectAll"    => "SELECT USER_NAMES, CONCAT(LAST_NAME_PAT,' ',LAST_NAME_MAT,' ',NAMES) AS NOMBRE, NR_DOC, FP.PROFILE_DESCRIPTION, WORKSTATION,STATUS_USER 
                            FROM FU_USERS FU
                            INNER JOIN FU_PROFILE FP ON FU.PROFILE_ID = FP.PROFILE_ID",
    "profilesSelectAll" => "SELECT * FROM FU_PROFILE ORDER BY PROFILE_ID DESC",
    "profilesSearchById" => "SELECT * FROM FU_PROFILE WHERE PROFILE_ID = {{idProfile}}",
    "profilesPermissions" => "SELECT * FROM FU_PROFILE WHERE PROFILE_ID = 1",
    "banksSelectAll"    => "SELECT * FROM FU_BANK_ACCOUNT B
                            INNER JOIN FU_COMPANY C ON C.COMPANY_ID = B.COMPANY_ID",
    "tipoDocSelectAll"  => "SELECT * FROM FU_TIPO_DOC",
    "areaSelectAll"     => "SELECT * FROM FU_AREA",
    "depositosSelectAll"=> "SELECT CONVERT(DATE,DATE_OPERATION) AS DATEOPER,* from FU_DEPOSIT 
                            WHERE SALDO<>0 AND DATEDIFF(DAY,DATE_OPERATION,GETDATE()) <=15
                            ORDER BY DATE_REG DESC",
    "localSelectAll"    => "SELECT SISCOD,SISENT FROM SISTEMA WHERE ESTADO='S' and SISCOD<>14 ORDER BY SISENT",
    "usersLolfarSelectAll" => "SELECT usecod,useusr,usenam,siscod FROM usuarios WHERE estado='S' AND usecod <> 1 AND grucod NOT IN ('SUPERV','GR0014','UINVEN')",
    "documentosDepLiq"  => "SELECT 
                            FDD.DEPOSIT_DET_ID,
                            S.siscod,
                            S.SISENT AS LOCAL_FAC,
                            CONVERT(DATE,F.facdat) AS FECHA_DOC,
                            F.invnum AS SECUENCIA,
                            CONCAT(LEFT(F.tdofac,1),TDOIDSER) AS SERIE,
                            F.facnum AS NRO_DOC,
                            F.facnet AS MONTO_TOTAL,
                            CONVERT(DATE,FD.DATE_OPERATION) AS FECHA_DEPOSITO,
                            FD.NAME_BANK AS BANCO,
                            FD.NR_OPERATION AS NR_OPERACION,
                            FDD.IMPORTE AS MONTO_DEPOSITADO,
                            LO.sisent AS LOCAL_ENTREGA,
                            FDD.USER_APPLICANT AS USUARIO_SOLICITANTE,
                            CONVERT(DATE,FDD.DATE_CONFIRMATION) AS FECH_CONFIR,
                            
                            CASE
                            WHEN inverd='S' THEN CONVERT(DATE,F.fecliq)
                            WHEN FFP.docdes = '__CREDITO' THEN CONVERT(DATE,FC.feccob)
                            END
                            AS FECHA_LIQ,
                            
                            CASE
                            WHEN inverd='S' THEN F.invnum_aperliq
                            WHEN FFP.docdes = '__CREDITO' THEN FC.invnum_aper
                            END
                            AS NRO_SECUENCIA,
                            
                            CASE
                            WHEN inverd='S' THEN MCD.usenam
                            WHEN FFP.docdes = '__CREDITO' THEN MCC.usenam
                            END
                            AS CAJERO,
                            
                            CASE
                            WHEN inverd='S' THEN CAJ.descaj
                            WHEN FFP.docdes = '__CREDITO' THEN CA.descaj
                            END
                            AS CAJA,
                            
                            CASE
                            WHEN F.facnet = (SELECT SUM(IMPORTE) FROM BD_INFORMES..FU_DEPOSIT_DET WHERE NR_SEQUENCE = F.INVNUM) THEN 'DEPOSITADO'
                            ELSE 'PENDIENTE' 
                            END AS DEPOSITO,
                            
                            CASE
                            WHEN inverd='S' and F.invnum_aperliq IS NOT NULL AND F.invnum_aperliq <> 0 THEN 'LIQUIDADO'
                            WHEN FFP.docdes = '__CREDITO' and F.facnet = (SELECT SUM(totcob) FROM fa_cobranzas WHERE movnum = F.movnum) THEN 'LIQUIDADO'
                            ELSE 'PENDIENTE' 
                            END AS LIQUIQ
                            
                            FROM FACTURAS F
                            INNER JOIN facturas_formas_pago FFP ON F.invnum = FFP.invnum
                            LEFT JOIN fa_cobranzas FC ON F.movnum = FC.movnum
                            LEFT JOIN movimientos_caja MCC ON MCC.invnum = FC.invnum_aper 
                            LEFT JOIN movimientos_caja MCD ON MCD.invnum = F.invnum_aperliq
                            LEFT JOIN cajeros CA ON CA.codcaj = FC.codcaj
                            LEFT JOIN cajeros CAJ ON CAJ.codcaj = F.codcaj_liq
                            LEFT JOIN sistema S ON S.siscod = F.siscod
                            LEFT JOIN BD_INFORMES..FU_DEPOSIT_DET FDD ON F.invnum = FDD.NR_SEQUENCE
                            LEFT JOIN sistema LO ON LO.siscod = FDD.LOCAL_GUIA
                            LEFT JOIN BD_INFORMES..FU_DEPOSIT FD ON FDD.DEPOSIT_ID = FD.DEPOSIT_ID
                            WHERE  (FFP.docdes = '__CREDITO' OR inverd='S') 
                            
                            {{WHERE_FECHA_BUSQUEDA}}
                            
                            AND 
                            CASE
                            WHEN F.facnet = (SELECT SUM(IMPORTE) FROM BD_INFORMES..FU_DEPOSIT_DET WHERE NR_SEQUENCE = F.INVNUM) THEN 1
                            ELSE 2
                            END IN ( {{deposito_estado}} )
                            AND
                            CASE
                            WHEN inverd='S' and F.invnum_aperliq IS NOT NULL THEN 1
                            WHEN FFP.docdes = '__CREDITO' and F.facnet = (SELECT SUM(totcob) FROM fa_cobranzas WHERE movnum = F.movnum) THEN 1
                            ELSE 2 
                            END IN ( {{liquid_estado}} )",
    "depositosTicket"   => "SELECT 
                                FB.COIN AS COIN,
                                FDD.DEPOSIT_DET_ID,
                                FDD.DATE_CONFIRMATION,
                                CONCAT(FB.DESCRIPTION_BANK,' ',FB.COIN) AS BANCO,
                                FD.NR_ACCOUNT AS NR_ACCOUNT, 
                                CONVERT(DATE,FD.DATE_OPERATION) AS FECHA_DEPOSITO,
                                FD.NR_OPERATION AS NR_OPERATION,
                                FD.REFERENCE_ONE AS REFERENCE_ONE,
                                FD.REFERENCE_TWO AS REFERENCE_TWO,
                            
                                FD.IMPORTE AS ABONO,
                                FDD.IMPORTE AS CANCELADO,
                                FD.SALDO AS FAVOR_CLIENTE,
                                FDD.SALDO AS FALTANTE,
                                
                                FDD.NAME_CLIENT,
                                CONCAT(LEFT(F.tdofac,1),TDOIDSER) AS SERIE,
                                F.facnum AS NRO_DOC,
                                F.facnet AS MONTO_TOTAL,
                                US.usenam AS USER_SOLICITA,
                                UC.usenam AS USER_CONFIRM

                            FROM FACTURAS F
                            INNER JOIN facturas_formas_pago FFP ON F.invnum = FFP.invnum
                            LEFT JOIN BD_INFORMES..FU_DEPOSIT_DET FDD ON F.invnum = FDD.NR_SEQUENCE
                            INNER JOIN usuarios US ON US.useusr = FDD.USER_APPLICANT 
                            INNER JOIN usuarios UC ON UC.useusr = FDD.USER_CONFIRM
                            LEFT JOIN BD_INFORMES..FU_DEPOSIT FD ON FDD.DEPOSIT_ID = FD.DEPOSIT_ID
                            LEFT JOIN BD_INFORMES..FU_BANK_ACCOUNT FB ON FD.BANK_ID = FB.BANK_ID
                            WHERE  (FFP.docdes = '__CREDITO' OR inverd='S') 
                            AND FDD.DEPOSIT_DET_ID = {{id}}",

    "ventasProductosRecomendados" => "SELECT	f.facdat [FECHA],
                                        s.sisent [LOCAL],
                                        f.pacnam [PACIENTE],
                                        vc.telcli [TELEFONO],
                                        vc.mednam [MEDICO],
                                        CASE 
                                            WHEN n.invnum_caj IS NOT NULL THEN 'NOTA DE CREDITO'
                                            WHEN f.tdofac = 'FA' THEN 'FACTURA' 
                                            ELSE 'BOLETA' 
                                        END [TIPO],	
                                        LEFT(f.tdofac,1)+f.tdoidser+'-'+CONVERT(VARCHAR,f.facnum) [COMPROBANTE],
                                        vd.codpro [SKU],
                                        vd.despro [PRODUCTO],
                                        vd.qtypro [UNIDADES],
                                        f.facnet [TOTAL],
                                        VC.invnum [SVenta],
                                        VD.dtopro/100 AS DESCUENTO

                                    FROM	LOLFAR9000.. fa_ventas_detalle vd WITH(NOLOCK)
                                        INNER JOIN LOLFAR9000.. fa_ventas_cabecera vc WITH(NOLOCK) on vd.invnum = vc.invnum
                                        INNER JOIN LOLFAR9000.. facturas f WITH(NOLOCK) ON vd.invnum = f.invnum_r
                                        LEFT JOIN co_notas_ventas_cabecera N ON f.invnum =  n.invnum_caj
                                        INNER JOIN LOLFAR9000.. sistema S WITH(NOLOCK) ON f.siscod = s.siscod
                                        
                                    WHERE	f.facsta <> 'A'
                                    AND CONVERT(DATE,F.facdat) >= '{{fecha_desde}}'
                                    AND CONVERT(DATE,F.facdat) <= '{{fecha_hasta}}'
                                    AND		vd.codpro IN ('24299','24300','25121','16022','15248','15294','15295','15296','15297','15298','15299','15300','15301','15302','15303','15304','24298','24297',
                                    '15344','15345','15346','15347','15348','15349','15370','15405','15420','15448','16323','15495','15497','16281','15622','16280','15623','15639',
                                    '15717','23425','15722','15736','15732','15734','15735','15738','15737','16173','16172','15825','15784','15785','15820','15795','16106','16103',
                                    '15907','16102','15906','16105','15901','16109','15928','15981','16004','16139','15804','16138','15215')",

    "etiquetasFMProd.Rec."  =>"SELECT 
                                VC.invnum [SECUENCIA],
                                f.pacnam [PACIENTE],
                                vd.despro [PRODUCTO],
                                f.facdat [FECHAE],
                                CASE
                                    WHEN VD.codpro IN ('15639','15784','15785','15906','15907','16004','16102','16103','16105','16106') THEN DATEADD(DAY,30,f.facdat)
                                    WHEN VD.codpro IN ('15420','15737','15738') THEN DATEADD(DAY,60,f.facdat)
                                    WHEN VD.codpro IN ('15294','15295','15296','15297','15298','15299','15300','15301','15302','15303','15304','15344','15345','15346','15347','15348','15349','15370','15405','15448','15495','15497',
                                                    '15732','15734','15735','15736','15795','15820','15825','15901','15981','16109','16172','16173','16323','15215') THEN DATEADD(DAY,90,f.facdat)
                                    WHEN VD.codpro IN ('15722') THEN DATEADD(DAY,120,f.facdat)
                                    WHEN VD.codpro IN ('15928') THEN DATEADD(DAY,160,f.facdat)
                                    WHEN VD.codpro IN ('15717','23425') THEN DATEADD(DAY,180,f.facdat)
                                    WHEN VD.codpro IN ('15248','15622','15623','15804','16022','16138','16139','16280','16281') THEN DATEADD(DAY,365,f.facdat)
                                END [FECHAV],
                                CASE
                                    WHEN VD.codpro IN ('15639') THEN 'GOTA OFTALMICA'
                                    WHEN VD.codpro IN ('15294','15295','15296','15297','15298','15299','15300','15301','15302','15303','15304','15344','15345','15346','15347','15348','15349','15370','15405','15420','15448','15495','15497','
                                                    15622','15623','15732','15734','15735','15736','15737','15738','15784','15785','15795','15820','15825','15901','15928','15981','16109','16172','16173','16280','16281','16323','15215') THEN 'USO EXTERNO'
                                    WHEN VD.codpro IN ('15248','15717','15722','15804','15906','15907','16004','16022','16102','16103','16105','16106','16138','16139','23425') THEN 'USO ORAL'
                                END [TIPOUSO]

                            FROM	LOLFAR9000.. fa_ventas_detalle vd WITH(NOLOCK)
                                INNER JOIN LOLFAR9000.. fa_ventas_cabecera vc WITH(NOLOCK) on vd.invnum = vc.invnum
                                INNER JOIN LOLFAR9000.. facturas f WITH(NOLOCK) ON vd.invnum = f.invnum_r
                                WHERE VC.invnum = {{id}}",



];      