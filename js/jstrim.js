 function trim(str)
  {
   return str.replace(/^\s+|\s+$/g,"");
  }

  function leftTrim(sString)
 {
  while (sString.substring(0,1) == ' ')
   {
    sString = sString.substring(1, sString.length);
   }
  return sString;
 }

function rightTrim(sString)
 {
  while (sString.substring(sString.length-1, sString.length) == ' ')
   {
    sString = sString.substring(0,sString.length-1);
   }
  return sString;
 }

function trimAll(sString)
 {
  while (sString.substring(0,1) == ' ')
   {
    sString = sString.substring(1, sString.length);
   }
  while (sString.substring(sString.length-1, sString.length) == ' ')
   {
    sString = sString.substring(0,sString.length-1);
   }
  return sString;
 }