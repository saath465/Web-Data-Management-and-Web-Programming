<categories>
{

for $c in doc('auction.xml')/site/categories/category

return
{
{$c/@id},
<Group-size>{count(doc('auction.xml')/site/people/person/profile/interest[@category = $c/@id])}</Group-size>


}


}
</categories>