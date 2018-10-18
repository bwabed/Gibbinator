using System;
using System.Collections.Generic;
using System.Text;

namespace Gibbinator.Models
{
    public class Class
    {
        public int ClassId { get; set; }
        public string Description { get; set; }
        public Teacher Classteacher { get; set; }
    }
}
