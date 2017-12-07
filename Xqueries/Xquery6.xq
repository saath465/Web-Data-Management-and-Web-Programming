<Europe-Auction>
{
        for $Item in doc('auction.xml')/site/regions/europe/item
        return
                {
                        for $a in doc('auction.xml')/site/closed_auctions/closed_auction
                        where $a/itemref/@item = $Item/@id
                        return
                                {
                                        for $p in doc('auction.xml')/site/people/person
                                        where $p/@id = $a/buyer/@person
                                        return

                                                        {$p/name},
                                                        <Item>{$Item/name}</Item>

                                }
                }
}
</Europe-Auction>

