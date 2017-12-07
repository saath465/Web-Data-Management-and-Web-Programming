for $a in doc('auction.xml')/site/open_auctions/open_auction
where $a/bidder/personref/@person = "person3" and $a/bidder/following-sibling::$
return
{
<AuctionItem>
                <reserve>{$a/reserve}</reserve>
</AuctionItem>

}
