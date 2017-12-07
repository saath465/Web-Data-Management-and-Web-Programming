for $cat in doc('auction.xml')/site/categories/*
        return {
                <category>
                {$cat/@id}
                {
                    for $p in doc('auction.xml')/site/people/*
                    return{
                         if ($p/profile//interest/@category = $cat/@id) then 			(<person>{$p/@id}{data($p/name)}</person>)
                         else()
                        }

                  }
                </category>
                }
