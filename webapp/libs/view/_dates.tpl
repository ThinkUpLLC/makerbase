
              <div class="form-group col-xs-12">
                <label for="role" class="col-sm-1 control-label hidden-xs">Dates</label>
                <div class="col-xs-12 col-sm-10">

                  <select name="from_month" id="from_month" class="form-input col-sm-2 col-xs-3 input">
                    <option value="">From</option>
                    <option value="01" {if isset($start_m)}{if $start_m eq '01'}selected="selected"{/if}{/if}>Jan</option>
                    <option value="02" {if isset($start_m)}{if $start_m eq '02'}selected="selected"{/if}{/if}>Feb</option>
                    <option value="03" {if isset($start_m)}{if $start_m eq '03'}selected="selected"{/if}{/if}>Mar</option>
                    <option value="04" {if isset($start_m)}{if $start_m eq '04'}selected="selected"{/if}{/if}>Apr</option>
                    <option value="05" {if isset($start_m)}{if $start_m eq '05'}selected="selected"{/if}{/if}>May</option>
                    <option value="06" {if isset($start_m)}{if $start_m eq '06'}selected="selected"{/if}{/if}>Jun</option>
                    <option value="07" {if isset($start_m)}{if $start_m eq '07'}selected="selected"{/if}{/if}>Jul</option>
                    <option value="08" {if isset($start_m)}{if $start_m eq '08'}selected="selected"{/if}{/if}>Aug</option>
                    <option value="09" {if isset($start_m)}{if $start_m eq '09'}selected="selected"{/if}{/if}>Sep</option>
                    <option value="10" {if isset($start_m)}{if $start_m eq '10'}selected="selected"{/if}{/if}>Oct</option>
                    <option value="11" {if isset($start_m)}{if $start_m eq '11'}selected="selected"{/if}{/if}>Nov</option>
                    <option value="12" {if isset($start_m)}{if $start_m eq '12'}selected="selected"{/if}{/if}>Dec</option>
                  </select>

                  <select name="from_year" id="from_year" class="form-input col-sm-2 col-xs-3 input">
                    <option value="">Year</option>
                    <option value="2015" {if isset($start_Y)}{if $start_Y eq '2015'}selected="selected"{/if}{/if}>2015</option>
                    <option value="2014" {if isset($start_Y)}{if $start_Y eq '2014'}selected="selected"{/if}{/if}>2014</option>
                    <option value="2013" {if isset($start_Y)}{if $start_Y eq '2013'}selected="selected"{/if}{/if}>2013</option>
                    <option value="2012" {if isset($start_Y)}{if $start_Y eq '2012'}selected="selected"{/if}{/if}>2012</option>
                    <option value="2011" {if isset($start_Y)}{if $start_Y eq '2011'}selected="selected"{/if}{/if}>2011</option>
                    <option value="2010" {if isset($start_Y)}{if $start_Y eq '2010'}selected="selected"{/if}{/if}>2010</option>

                    <option value="2009" {if isset($start_Y)}{if $start_Y eq '2009'}selected="selected"{/if}{/if}>2009</option>
                    <option value="2008" {if isset($start_Y)}{if $start_Y eq '2008'}selected="selected"{/if}{/if}>2008</option>
                    <option value="2007" {if isset($start_Y)}{if $start_Y eq '2007'}selected="selected"{/if}{/if}>2007</option>
                    <option value="2006" {if isset($start_Y)}{if $start_Y eq '2006'}selected="selected"{/if}{/if}>2006</option>
                    <option value="2005" {if isset($start_Y)}{if $start_Y eq '2005'}selected="selected"{/if}{/if}>2005</option>
                    <option value="2004" {if isset($start_Y)}{if $start_Y eq '2004'}selected="selected"{/if}{/if}>2004</option>
                    <option value="2003" {if isset($start_Y)}{if $start_Y eq '2003'}selected="selected"{/if}{/if}>2003</option>
                    <option value="2002" {if isset($start_Y)}{if $start_Y eq '2002'}selected="selected"{/if}{/if}>2002</option>
                    <option value="2001" {if isset($start_Y)}{if $start_Y eq '2001'}selected="selected"{/if}{/if}>2001</option>
                    <option value="2000" {if isset($start_Y)}{if $start_Y eq '2000'}selected="selected"{/if}{/if}>2000</option>

                    <option value="1999" {if isset($start_Y)}{if $start_Y eq '1999'}selected="selected"{/if}{/if}>1999</option>
                    <option value="1998" {if isset($start_Y)}{if $start_Y eq '1998'}selected="selected"{/if}{/if}>1998</option>
                    <option value="1997" {if isset($start_Y)}{if $start_Y eq '1997'}selected="selected"{/if}{/if}>1997</option>
                    <option value="1996" {if isset($start_Y)}{if $start_Y eq '1996'}selected="selected"{/if}{/if}>1996</option>
                    <option value="1995" {if isset($start_Y)}{if $start_Y eq '1995'}selected="selected"{/if}{/if}>1995</option>
                    <option value="1994" {if isset($start_Y)}{if $start_Y eq '1994'}selected="selected"{/if}{/if}>1994</option>
                    <option value="1993" {if isset($start_Y)}{if $start_Y eq '1993'}selected="selected"{/if}{/if}>1993</option>
                    <option value="1992" {if isset($start_Y)}{if $start_Y eq '1992'}selected="selected"{/if}{/if}>1992</option>
                    <option value="1991" {if isset($start_Y)}{if $start_Y eq '1991'}selected="selected"{/if}{/if}>1991</option>
                    <option value="1990" {if isset($start_Y)}{if $start_Y eq '1990'}selected="selected"{/if}{/if}>1990</option>

                    <option value="1989" {if isset($start_Y)}{if $start_Y eq '1989'}selected="selected"{/if}{/if}>1989</option>
                    <option value="1988" {if isset($start_Y)}{if $start_Y eq '1988'}selected="selected"{/if}{/if}>1988</option>
                    <option value="1987" {if isset($start_Y)}{if $start_Y eq '1987'}selected="selected"{/if}{/if}>1987</option>
                    <option value="1986" {if isset($start_Y)}{if $start_Y eq '1986'}selected="selected"{/if}{/if}>1986</option>
                    <option value="1985" {if isset($start_Y)}{if $start_Y eq '1985'}selected="selected"{/if}{/if}>1985</option>
                    <option value="1984" {if isset($start_Y)}{if $start_Y eq '1984'}selected="selected"{/if}{/if}>1984</option>
                    <option value="1983" {if isset($start_Y)}{if $start_Y eq '1983'}selected="selected"{/if}{/if}>1983</option>
                    <option value="1982" {if isset($start_Y)}{if $start_Y eq '1982'}selected="selected"{/if}{/if}>1982</option>
                    <option value="1981" {if isset($start_Y)}{if $start_Y eq '1981'}selected="selected"{/if}{/if}>1981</option>
                    <option value="1980" {if isset($start_Y)}{if $start_Y eq '1980'}selected="selected"{/if}{/if}>1980</option>

                    <option value="1979" {if isset($start_Y)}{if $start_Y eq '1979'}selected="selected"{/if}{/if}>1979</option>
                    <option value="1978" {if isset($start_Y)}{if $start_Y eq '1978'}selected="selected"{/if}{/if}>1978</option>
                    <option value="1977" {if isset($start_Y)}{if $start_Y eq '1977'}selected="selected"{/if}{/if}>1977</option>
                    <option value="1976" {if isset($start_Y)}{if $start_Y eq '1976'}selected="selected"{/if}{/if}>1976</option>
                    <option value="1975" {if isset($start_Y)}{if $start_Y eq '1975'}selected="selected"{/if}{/if}>1975</option>
                    <option value="1974" {if isset($start_Y)}{if $start_Y eq '1974'}selected="selected"{/if}{/if}>1974</option>
                    <option value="1973" {if isset($start_Y)}{if $start_Y eq '1973'}selected="selected"{/if}{/if}>1973</option>
                    <option value="1972" {if isset($start_Y)}{if $start_Y eq '1972'}selected="selected"{/if}{/if}>1972</option>
                    <option value="1971" {if isset($start_Y)}{if $start_Y eq '1971'}selected="selected"{/if}{/if}>1971</option>
                    <option value="1970" {if isset($start_Y)}{if $start_Y eq '1970'}selected="selected"{/if}{/if}>1970</option>
                  </select>

                  <select name="to_month" id="to_month" class="form-input col-sm-2 col-sm-offset-1 col-xs-3 input">
                    <option value="">To</option>
                    <option value="01" {if isset($end_m)}{if $end_m eq '01'}selected="selected"{/if}{/if}>Jan</option>
                    <option value="02" {if isset($end_m)}{if $end_m eq '02'}selected="selected"{/if}{/if}>Feb</option>
                    <option value="03" {if isset($end_m)}{if $end_m eq '03'}selected="selected"{/if}{/if}>Mar</option>
                    <option value="04" {if isset($end_m)}{if $end_m eq '04'}selected="selected"{/if}{/if}>Apr</option>
                    <option value="05" {if isset($end_m)}{if $end_m eq '05'}selected="selected"{/if}{/if}>May</option>
                    <option value="06" {if isset($end_m)}{if $end_m eq '06'}selected="selected"{/if}{/if}>Jun</option>
                    <option value="07" {if isset($end_m)}{if $end_m eq '07'}selected="selected"{/if}{/if}>Jul</option>
                    <option value="08" {if isset($end_m)}{if $end_m eq '08'}selected="selected"{/if}{/if}>Aug</option>
                    <option value="09" {if isset($end_m)}{if $end_m eq '09'}selected="selected"{/if}{/if}>Sep</option>
                    <option value="10" {if isset($end_m)}{if $end_m eq '10'}selected="selected"{/if}{/if}>Oct</option>
                    <option value="11" {if isset($end_m)}{if $end_m eq '11'}selected="selected"{/if}{/if}>Nov</option>
                    <option value="12" {if isset($end_m)}{if $end_m eq '12'}selected="selected"{/if}{/if}>Dec</option>
                  </select>

                  <select name="to_year" id="to_year" class="form-input col-sm-2 col-xs-3 input">
                    <option value="">Present</option>
                    <option value="2015" {if isset($end_Y)}{if $end_Y eq '2015'}selected="selected"{/if}{/if}>2015</option>
                    <option value="2014" {if isset($end_Y)}{if $end_Y eq '2014'}selected="selected"{/if}{/if}>2014</option>
                    <option value="2013" {if isset($end_Y)}{if $end_Y eq '2013'}selected="selected"{/if}{/if}>2013</option>
                    <option value="2012" {if isset($end_Y)}{if $end_Y eq '2012'}selected="selected"{/if}{/if}>2012</option>
                    <option value="2011" {if isset($end_Y)}{if $end_Y eq '2011'}selected="selected"{/if}{/if}>2011</option>
                    <option value="2010" {if isset($end_Y)}{if $end_Y eq '2010'}selected="selected"{/if}{/if}>2010</option>

                    <option value="2009" {if isset($end_Y)}{if $end_Y eq '2009'}selected="selected"{/if}{/if}>2009</option>
                    <option value="2008" {if isset($end_Y)}{if $end_Y eq '2008'}selected="selected"{/if}{/if}>2008</option>
                    <option value="2007" {if isset($end_Y)}{if $end_Y eq '2007'}selected="selected"{/if}{/if}>2007</option>
                    <option value="2006" {if isset($end_Y)}{if $end_Y eq '2006'}selected="selected"{/if}{/if}>2006</option>
                    <option value="2005" {if isset($end_Y)}{if $end_Y eq '2005'}selected="selected"{/if}{/if}>2005</option>
                    <option value="2004" {if isset($end_Y)}{if $end_Y eq '2004'}selected="selected"{/if}{/if}>2004</option>
                    <option value="2003" {if isset($end_Y)}{if $end_Y eq '2003'}selected="selected"{/if}{/if}>2003</option>
                    <option value="2002" {if isset($end_Y)}{if $end_Y eq '2002'}selected="selected"{/if}{/if}>2002</option>
                    <option value="2001" {if isset($end_Y)}{if $end_Y eq '2001'}selected="selected"{/if}{/if}>2001</option>
                    <option value="2000" {if isset($end_Y)}{if $end_Y eq '2000'}selected="selected"{/if}{/if}>2000</option>

                    <option value="1999" {if isset($end_Y)}{if $end_Y eq '1999'}selected="selected"{/if}{/if}>1999</option>
                    <option value="1998" {if isset($end_Y)}{if $end_Y eq '1998'}selected="selected"{/if}{/if}>1998</option>
                    <option value="1997" {if isset($end_Y)}{if $end_Y eq '1997'}selected="selected"{/if}{/if}>1997</option>
                    <option value="1996" {if isset($end_Y)}{if $end_Y eq '1996'}selected="selected"{/if}{/if}>1996</option>
                    <option value="1995" {if isset($end_Y)}{if $end_Y eq '1995'}selected="selected"{/if}{/if}>1995</option>
                    <option value="1994" {if isset($end_Y)}{if $end_Y eq '1994'}selected="selected"{/if}{/if}>1994</option>
                    <option value="1993" {if isset($end_Y)}{if $end_Y eq '1993'}selected="selected"{/if}{/if}>1993</option>
                    <option value="1992" {if isset($end_Y)}{if $end_Y eq '1992'}selected="selected"{/if}{/if}>1992</option>
                    <option value="1991" {if isset($end_Y)}{if $end_Y eq '1991'}selected="selected"{/if}{/if}>1991</option>
                    <option value="1990" {if isset($end_Y)}{if $end_Y eq '1990'}selected="selected"{/if}{/if}>1990</option>

                    <option value="1989" {if isset($end_Y)}{if $end_Y eq '1989'}selected="selected"{/if}{/if}>1989</option>
                    <option value="1988" {if isset($end_Y)}{if $end_Y eq '1988'}selected="selected"{/if}{/if}>1988</option>
                    <option value="1987" {if isset($end_Y)}{if $end_Y eq '1987'}selected="selected"{/if}{/if}>1987</option>
                    <option value="1986" {if isset($end_Y)}{if $end_Y eq '1986'}selected="selected"{/if}{/if}>1986</option>
                    <option value="1985" {if isset($end_Y)}{if $end_Y eq '1985'}selected="selected"{/if}{/if}>1985</option>
                    <option value="1984" {if isset($end_Y)}{if $end_Y eq '1984'}selected="selected"{/if}{/if}>1984</option>
                    <option value="1983" {if isset($end_Y)}{if $end_Y eq '1983'}selected="selected"{/if}{/if}>1983</option>
                    <option value="1982" {if isset($end_Y)}{if $end_Y eq '1982'}selected="selected"{/if}{/if}>1982</option>
                    <option value="1981" {if isset($end_Y)}{if $end_Y eq '1981'}selected="selected"{/if}{/if}>1981</option>
                    <option value="1980" {if isset($end_Y)}{if $end_Y eq '1980'}selected="selected"{/if}{/if}>1980</option>

                    <option value="1979" {if isset($end_Y)}{if $end_Y eq '1979'}selected="selected"{/if}{/if}>1979</option>
                    <option value="1978" {if isset($end_Y)}{if $end_Y eq '1978'}selected="selected"{/if}{/if}>1978</option>
                    <option value="1977" {if isset($end_Y)}{if $end_Y eq '1977'}selected="selected"{/if}{/if}>1977</option>
                    <option value="1976" {if isset($end_Y)}{if $end_Y eq '1976'}selected="selected"{/if}{/if}>1976</option>
                    <option value="1975" {if isset($end_Y)}{if $end_Y eq '1975'}selected="selected"{/if}{/if}>1975</option>
                    <option value="1974" {if isset($end_Y)}{if $end_Y eq '1974'}selected="selected"{/if}{/if}>1974</option>
                    <option value="1973" {if isset($end_Y)}{if $end_Y eq '1973'}selected="selected"{/if}{/if}>1973</option>
                    <option value="1972" {if isset($end_Y)}{if $end_Y eq '1972'}selected="selected"{/if}{/if}>1972</option>
                    <option value="1971" {if isset($end_Y)}{if $end_Y eq '1971'}selected="selected"{/if}{/if}>1971</option>
                    <option value="1970" {if isset($end_Y)}{if $end_Y eq '1970'}selected="selected"{/if}{/if}>1970</option>
                  </select>
                </div>

                <input type="hidden" name="start_date" id="start_date" placeholder="YYYY-MM" autocomplete="off" value="{if isset($role->start_YM)}{$role->start_YM}{/if}"/>
                <input type="hidden" name="end_date" id="end_date" autocomplete="off"  value="{if isset($role->end_YM)}{$role->end_YM}{/if}" />


              </div>