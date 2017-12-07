<Europe-Items>
{
for $x in doc("auction.xml")/site/regions/europe/*
return
        {
                <Item>
                        <Item-Name>{$x/name}</Item-Name>
                        <Item-Description>{$x/description}</Item-Description>
                </Item>
        }

}
</Europe-Items>