<?php

/*
 * gsFrontend
 * Copyright (C) 2011 Gedankengut GbR Häuser & Sirin <support@gsales.de>
 * 
 * This file is part of gsFrontend.
 * 
 * gsFrontend is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * gsFrontend is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with gsFrontend. If not, see <http://www.gnu.org/licenses/>.
 */

class GSALES_DATA_CONTRACT extends GSALES_DATA{

	public function __construct(){
		parent::__construct();
	}
	
	public function getContractPositionsByCustomerId($intCustomerId){
		$arrFilter[] = array('field'=>'customers_id', 'operator'=>'is', 'value'=>$intCustomerId);
		$arrFilter[] = array('field'=>'active', 'operator'=>'is', 'value'=>'1');
		$arrSort = array('field'=>'created', 'direction'=>'desc');
		$arrResult = $this->objSoapClient->getContracts($this->strAPIKey, $arrFilter, $arrSort, 999, 0);
		if ($arrResult['status']->code != 0) throw new Exception($arrResult['status']->message,$arrResult['status']->code);
		$objMultiPos = array();
		foreach ((array)$arrResult['result'] as $key => $contract) {
			$objContract = new GSALES2_OBJECT_CONTRACT($contract);
			foreach ((array)$objContract->getPositions() as $key => $objPos){
				$objMultiPos[] = new GSALES2_OBJECT_CONTRACT_MULTIPOS($objPos, $objContract);
			}
		}
		return $objMultiPos;
	}
	
}