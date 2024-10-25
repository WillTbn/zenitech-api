SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `zenitech`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    date_of_birth DATE,
    photo VARCHAR(255) DEFAULT 'uploads/default.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `clients`
--

INSERT INTO users (name, email, date_of_birth)
VALUES ('Alice Johnson', 'alice.johnson@example.com', '1990-02-15'),
    ('Bob Smith', 'bob.smith@example.com', '1985-07-22'),
    ('Carol White', 'carol.white@example.com', '1992-11-30'),
    ('David Brown', 'david.brown@example.com', '1988-04-05'),
    ('Eva Green', 'eva.green@example.com', '1995-08-14'),
    ('Frank Black', 'frank.black@example.com', '1983-09-25'),
    ('Grace King', 'grace.king@example.com', '1991-12-10'),
    ('Henry Stone', 'henry.stone@example.com', '1986-01-17'),
    ('Ivy Moore', 'ivy.moore@example.com', '1993-03-21'),
    ('Jack Hill', 'jack.hill@example.com', '1989-05-29');


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;