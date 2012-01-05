$res1=mysql_query("select * from ".$row[2]."details where $a like '%$_GET[$a]%';");
			$row1=mysql_fetch_array($res1);
			echo "select * from $row[2] where $a like '%$_GET[$a]%';";
			while($row1=mysql_fetch_array($res1))
			{
				$res2=mysql_query("select attrib from resource_attrib natural join type where description=$row[2];");
				echo "select attrib from resource_attrib natural join type where description='$row[2]'";
				while($row2=mysql_fetch_array($res2))
				{
					$c=mysql_num_rows($row2);
					for($i=0;$i<=$c;$i++)
					{
						$response.="<$row2>$row1[0]</$row2>";
					}
					echo $row2[1];
				}
				
			}
			
