
using System;
using System.Collections.Generic;
using System.Text;

namespace Gibbinator.Models
{
    public class Room
    {
        public int RoomId { get; set; }
        public string Description { get; set; }
        public string Number { get; set; }
        public int Floor { get; set; }
        public Building Building { get; set; }
    }
}
