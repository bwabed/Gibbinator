using System;
using System.Collections.Generic;
using System.Text;

namespace Gibbinator.Models
{
    public class Teacher
    {
        public int TeacherId { get; set; }
        public string Name { get; set; }
        public string Surname { get; set; }
        public string Email { get; set; }
        public string Telephone { get; set; }
        public string Password { get; set; }

        public string FullName
        {
            get
            {
                return Name + " " + Surname;
            }
        }
    }
}
