					<form enctype="multipart/form-data" method=post action="index.php?module=clearRules&ID=<?php
echo $_obj['id'];
?>
&action=submit">
					<center>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse">
                        <td width="35%" background="images/tb_background.gif" height="25"> 
                          <center>
                            <font color="#FFFFFF">清空采集器数据</font> 
                          </center>
                        </td>
                        <td background="images/tb_background.gif"></td>
                      <tr onMouseOver="this.style.backgroundColor = '#F7F7F7'" onMouseOut="this.style.backgroundColor = ''"> 
                        <td style="border: 1px solid #CCCCCC" height="30" bgcolor="#F7F7F7" width="35%" align="right">采集器名称&nbsp;&nbsp;</td>
                        <td style="border: 1px solid #CCCCCC" height="30" width="65%"> 
							&nbsp;&nbsp;<?php
echo $_obj['ruleName'];
?>

                        </td>
                      </tr>
                      <tr onMouseOver="this.style.backgroundColor = '#F7F7F7'" onMouseOut="this.style.backgroundColor = ''"> 
                        <td style="border: 1px solid #CCCCCC" height="30" bgcolor="#F7F7F7" width="35%" align="right">所在分类&nbsp;&nbsp;</td>
                        <td style="border: 1px solid #CCCCCC" height="30" width="65%"> 
							&nbsp;&nbsp;<?php
echo $_obj['cateName'];
?>

                        </td>
                      </tr>
                      <tr onMouseOver="this.style.backgroundColor = '#F7F7F7'" onMouseOut="this.style.backgroundColor = ''"> 
                        <td style="border: 1px solid #CCCCCC" height="30" bgcolor="#F7F7F7" width="35%" align="right">连接数据&nbsp;&nbsp;</td>
                        <td style="border: 1px solid #CCCCCC" height="30" width="65%"> 
							&nbsp;&nbsp;<?php
echo $_obj['linkNum'];
?>

                        </td>
                      </tr>
                      <tr onMouseOver="this.style.backgroundColor = '#F7F7F7'" onMouseOut="this.style.backgroundColor = ''"> 
                        <td style="border: 1px solid #CCCCCC" height="30" bgcolor="#F7F7F7" width="35%" align="right">入库数据&nbsp;&nbsp;</td>
                        <td style="border: 1px solid #CCCCCC" height="30" width="65%"> 
							&nbsp;&nbsp;<?php
echo $_obj['importNum'];
?>

                        </td>
                      </tr>
                      </tr>
                      <tr onMouseOver="this.style.backgroundColor = '#F7F7F7'" onMouseOut="this.style.backgroundColor = ''"> 
                        <td style="border: 1px solid #CCCCCC" height="30" bgcolor="#F7F7F7" width="35%" align="right">选择要清空的数据类型&nbsp;&nbsp;</td>
                        <td style="border: 1px solid #CCCCCC" height="30" width="65%"> 
							&nbsp; <input type="checkbox" name="link"> 连接数据&nbsp;&nbsp; <input type="checkbox" name="data"> 入库数据
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" bgcolor="#EFEFEF" style="border: 1px solid #CCCCCC" colspan="2"> 
                          <p align="right"> 
                            <input type="submit" value="清空数据" class="button">
                            &nbsp;&nbsp; 
                        </td>
                      </tr>
                    </table>
					</center>
					</form>