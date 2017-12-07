<people>
{
for $p in doc('auction.xml')/site/people/*
return

        <person>
                {
                if($p/@id = doc('auction.xml')/site/closed_auctions/closed_auction/buyer/@person) then (
                                
                                <name>{$p/name/text()}</name>,
                                <number-of-items-bought>{count(doc('auction.xml')/site/closed_auctions/closed_auction/buyer[@person=$p/@id])}</number-of-items-bought>
                                )
                else (
                        
                        <name>{$p/name/text()}</name>,
                        <number-of-items-bought>0</number-of-items-bought>
                     )
                }
        </person>
}

</people>
