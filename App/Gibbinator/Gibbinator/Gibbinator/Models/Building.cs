using System;
using System.Collections.Generic;
using System.Text;

namespace Gibbinator.Models
{
    public class Building
    {
        public int BuildingId { get; set; }
        public string Description { get; set; }
        public string Street { get; set; }
        public string StreetNumber { get; set; }
        public int ZipCode { get; set; }
        public string City { get; set; }
    }
}
