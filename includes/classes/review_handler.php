<?php

 class review_handler
 {
 	var $childReviewPadding, $dataset;
	
	function getReviewCount()
	{
		return mysql_num_rows($this->dataset);
	}
	
	public function review_handler($d)
	{
		$this->childReviewPadding = 0;
		$this->nodeLevel = 0;
		$this->dataset = $d;
    }
	
	public function setNodeLevelToOne()	{ $this->nodeLevel = 0;}
	public function incrementNodeLevel()	{ $this->nodeLevel++;}
	public function getNodeLevel()	
	{
		$level = $this->nodeLevel;
		$this->setNodeLevelToOne();
		return $level;
	}
	
	public function printChildren($data, $parent_id)
	{
		$children = $this->fetchChildReviews($data, $parent_id);		

		if (count($children) > 0)
		{
			for ($i=0; $i<count($children); $i++)
			{
				$this->writeToPage($children[$i]);
				$this->printChildren($data, $children[$i]['reviews_id']);
			}	
		}	
	}
	
	public function writeToPage($row)
	{
		global $HTTP_GET_VARS;
		$review_id = $HTTP_GET_VARS['review'];
		
		$nodeLevel = $this->determineLevel($this->dataset, $row['reviews_id']);
		$childReviewPadding = $nodeLevel * 8;
		
		$contents = 
		'<tr>';
		
		if ($review_id != $row['reviews_id'])
		{
			$contents .= '<td style="padding:15px 50px 5px '.(int)$childReviewPadding.'px;" class="review_node">';
			$hyperlink = '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'review=' . $row['reviews_id']) . '"><u><b>';
            		  
		  if ($row['use'] == NULL) { $hyperlink .= 'Review From '.$row['customers_name']; }
		  else 					   { $hyperlink .= $row['use']; } 	
		  $hyperlink .= '</b></u></a>';	
		}
		else
		{
		  $contents .= '<td style="padding:15px 0 5px '.(int)$childReviewPadding.'px;" class="review_node">';
		  $hyperlink = '<b>';
		  
		  if ($row['use'] == NULL) { $hyperlink .= 'Review From '.$row['customers_name']; }
		  else 					   { $hyperlink .= $row['use']; } 	
		  
		  $hyperlink .= '</b>';
		}
		  
	    $contents .= $hyperlink;
	    $contents .= 
		'</td>
		<td style="padding:15px 0 5px 0" class="review_date" align="right">'.
			sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($row['date_added'])).
		'</td>
		</tr>';
		
		echo $contents;
	}
	public function fetchChildReviews($data, $parent_id)
	{
		$children = array();
		
		$total = mysql_num_rows($data);
		for ($i=0; $i<$total; $i++)
		{
			mysql_data_seek($data, $i);
			$row = mysql_fetch_assoc($data);
			
			if ((int)$row['review_parent_id'] == $parent_id)
			{
				$children[] = $row;
			}
		}
		return $children;
	}

	public function determineLevel($data, $reviewId)
	{
		for ($i=0; $i<mysql_num_rows($data); $i++)
		{
			mysql_data_seek($data, $i);
			$row = mysql_fetch_assoc($data);
						
			if ((int)$row['reviews_id'] == $reviewId)
			{
				if ((int)$row['review_parent_id'] == NULL)
				{
					//echo 'Id: '. $row['reviews_id'].' : '.$this->getNodeLevel().'<br />';
					return $this->getNodeLevel();
				}
				else 
				{
					$this->incrementNodeLevel();
					return $this->determineLevel($data, (int)$row['review_parent_id']);	
				}
			}
		}
	}
	
	public function getParent($data, $reviewId)
	{
		for ($i=0; $i<mysql_num_rows($data); $i++)
		{
			mysql_data_seek($data, $i);
			$row = mysql_fetch_assoc($data);
						
			if ((int)$row['reviews_id'] == $reviewId)
			{
				if ((int)$row['review_parent_id'] == NULL)
				{
					return $row;
				}
				else 
				{
					return $this->getParent($data, (int)$row['review_parent_id']);	
				}
			}
		}
	}
	/*
	 public function determineLevel($data, $reviewId)
	{
		for ($i=0; $i<mysql_num_rows($data); $i++)
		{
			mysql_data_seek($data, $i);
			$row = mysql_fetch_assoc($data);
						
			if ((int)$row['reviews_id'] == $reviewId)
			{
				if ((int)$row['review_parent_id'] == NULL)
				{
					//echo 'Id: '. $row['reviews_id'].' : '.$this->getNodeLevel().'<br />';
					return $this->getNodeLevel();
				}
				else 
				{
					$this->incrementNodeLevel();
					return $this->determineLevel($data, (int)$row['review_parent_id']);	
				}
			}
		}
	}
	 */
 }

?>