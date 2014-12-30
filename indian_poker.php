<?php
	
	$intCardCnt = 5;
	$intMemberCnt = 3;
	$arrSelect = array(1 => 1, 2 => 2, 3 => 5);
	$arrExcept = array();
	$arrExpect = array();
	
	for ($i = 1;$i <= $intMemberCnt;$i++) {
		//自分以外の値(除外リスト)を保持
		for ($j = 1;$j <= $intMemberCnt;$j++) {
			if($i != $j) {
				$arrExcept[$i][] = $arrSelect[$j];
			}
		}
		
		//期待値(候補)を保持
		for ($j = 1;$j <= $intCardCnt;$j++) {
			if (!in_array($j, $arrExcept[$i])) {
				$arrExpect[$i][] = $j;
			}
		}
	}
	
	//1順目
	print "■■■1順目■■■\n";
	for ($i = 1;$i <= $intMemberCnt;$i++) {
		for ($j = 1;$j < $i;$j++) {
			//前判定処理
			prejudge($i, $j, $arrSelect, $arrExpect, $intCardCnt, $intMemberCnt);
		}
		
		//判定処理
		judge($i,$arrExpect[$i], $arrExcept[$i], true);
	}
	
	//2順目
	print "\n■■■2順目■■\n";
	for ($i = 1;$i <= $intMemberCnt;$i++) {
		for ($j = $intMemberCnt;$j > $i;$j--) {
			//前判定処理
			prejudge($i, $j, $arrSelect, $arrExpect, $intCardCnt, $intMemberCnt);
		}
		
		//判定処理
		judge($i,$arrExpect[$i], $arrExcept[$i], true);
	}
	
	//前判定処理
	function prejudge($intSelf, $intTarget, $arrSelect, &$arrExpect, $intCardCnt, $intMemberCnt) {
		//変数初期化
		$arrLookedNum = array();
		$arrCompExpect = $arrExpect;
		
		//①自分と比較対象で共通して参照している値を保持
		for ($i = 1;$i <= $intMemberCnt;$i++) {
			if ($arrSelect[$intSelf] != $arrSelect[$i] && $arrSelect[$intTarget] != $arrSelect[$i]) {
				$arrLookedNum[] = $arrSelect[$i];
			}
		}
		
		//②比較対象が取り得る値を判定
		for ($i = 0;$i < count($arrCompExpect[$intSelf]);$i++) {
			//比較対象用の除外リストを作成
			$arrTempExcept = $arrLookedNum;
			$arrTempExcept[] = $arrCompExpect[$intSelf][$i];
			
			//比較対象用の期待値(候補)を作成
			$arrTempExpect = array();
			for ($j = 1;$j <= $intCardCnt;$j++) {
				if (!in_array($j, $arrTempExcept)) {
					$arrTempExpect[] = $j;
				}
			}
			
			//判定処理
			if (judge($intTarget, $arrTempExpect, $arrTempExcept)) {
				//trueの場合、結果が事前に分かっていることになるため、期待値(候補)から除外
				$arrTemp = array();
				foreach ($arrExpect[$intTarget] as $intKey => $intValue) {
					if ($intValue != $arrCompExpect[$intSelf][$i]) {
						$arrTemp[] = $intValue;
					}
				}
				$arrExpect[$intTarget] = $arrTemp;
			}
		}
	}
	
	//判定処理
	function judge($intMember, $arrExpect, $arrExcept, $blnLog=false) {
		//変数初期化
		$arrJudge = array();
		
		//インデックス位置をチェック
		for ($i = 0;$i < count($arrExpect);$i++) {
			//自分以外の値と期待値をマージし昇順に並び変える
			$arrMerge = $arrExcept;
			$arrMerge[] = $arrExpect[$i];
			sort($arrMerge);
			
			//インデックス位置を保持
			for ($j = 0;$j < count($arrMerge);$j++) {
				if ($arrExpect[$i] == $arrMerge[$j]) {
					$arrJudge[$j] = $j + 1;
				}
			}
		}
		
		//判定処理
		if (count($arrJudge) == 1) {
			foreach ($arrJudge as $intValue) { 
				if($blnLog) print "{$intMember}人目：{$intValue}\n";
			}
			return true;
		}else{
			if($blnLog) print "{$intMember}人目：?\n";
			return false;
		}
	}
	
?>