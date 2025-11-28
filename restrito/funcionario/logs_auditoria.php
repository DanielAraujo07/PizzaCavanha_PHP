<?php
include "../verifica_login.php";
include "../conexao.php";

// Verificar permissão (nível Admin para ver logs)
if ($_SESSION['class_nivel'] < 6) {
    header('Location: ../index.php');
    exit();
}

// Buscar logs com informações do usuário
$sql_logs = "
    SELECT la.*, u.nome as usuario_nome, u.email as usuario_email 
    FROM logs_auditoria la 
    LEFT JOIN users u ON la.id_usuario = u.id 
    ORDER BY la.data_hora DESC 
    LIMIT 100
";
$result_logs = mysqli_query($conn, $sql_logs);
$logs = mysqli_fetch_all($result_logs, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs de Auditoria</title>
    <link rel="shortcut icon" href="../assets/funcionario.png"/>

    <!-- Fontes Oswald, Jaro e Rajdhani -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jaro:opsz@6..72&family=Oswald:wght@200..700&display=swap" rel="stylesheet">

    <!-- Icones Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/18b2c31938.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/css/funcionario.css">
</head>
<body>
    <header class="admin-header">
        <nav class="admin-nav">
            <div class="admin-logo">
                <h1><i class="fas fa-clipboard-list"></i> Logs de Auditoria</h1>
            </div>
            <div class="admin-user">
                <div class="admin-user-info">
                    <div class="admin-user-name"><?php echo htmlspecialchars($_SESSION['nome']); ?></div>
                    &bull;
                    <div class="admin-user-role"><?php echo htmlspecialchars($_SESSION['class_nome']); ?></div>
                </div>

                <!-- Botão de Toggle Tema -->
                <button class="theme-toggle" id="themeToggle" title="Alternar tema">
                    <i class="fas fa-moon" id="themeIcon"></i>
                </button>

                <a href="../../logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </div>
        </nav>
    </header>

    <div class="admin-container">
        <aside class="admin-sidebar">
            <ul class="admin-menu">
                <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>

                <?php if ($_SESSION['class_nivel'] >= 4): ?>
                    <li><a href="produtos.php"><i class="fas fa-pizza-slice"></i> Produtos</a></li>
                <?php endif; ?>

                <?php if ($_SESSION['class_nivel'] >= 4): ?>
                    <li><a href="ingredientes.php"><i class="fas fa-carrot"></i> Ingredientes</a></li>
                <?php endif; ?>

                <?php if ($_SESSION['class_nivel'] >= 2): ?>
                    <li><a href="pedidos.php"><i class="fas fa-shopping-cart"></i> Pedidos</a></li>
                <?php endif; ?>

                <?php if ($_SESSION['class_nivel'] >= 5): ?>
                    <li><a href="categorias.php"><i class="fas fa-tag"></i> Categorias</a></li>
                <?php endif; ?>

                <?php if ($_SESSION['class_nivel'] >= 6): ?>
                    <li><a href="usuarios.php"><i class="fas fa-users"></i> Usuários</a></li>
                <?php endif; ?>

                <?php if ($_SESSION['class_nivel'] >= 5): ?>
                    <li><a href="relatorios.php"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
                <?php endif; ?>

                <?php if ($_SESSION['class_nivel'] >= 6): ?>
                    <li><a href="logs_auditoria.php"><i class="fas fa-clipboard-list"></i> Logs de Auditoria</a></li>
                <?php endif; ?>

                <li><a href="../index.php"><i class="fas fa-home"></i> Voltar à Home</a></li>
            </ul>
        </aside>

        <main class="admin-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Logs de Auditoria</h1>
                    <p>Registro de todas as alterações no sistema</p>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Data/Hora</th>
                            <th>Usuário</th>
                            <th>Tabela</th>
                            <th>Ação</th>
                            <th>ID Registro</th>
                            <th>Detalhes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($log['data_hora'])); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($log['usuario_nome']); ?></strong><br>
                                    <small><?php echo htmlspecialchars($log['usuario_email']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($log['tabela_afetada']); ?></td>
                                <td>
                                    <span class="status-badge 
                                        <?php echo $log['acao'] == 'INSERT' ? 'status-active' : 
                                              ($log['acao'] == 'UPDATE' ? 'status-warning' : 'status-error'); ?>">
                                        <?php echo $log['acao']; ?>
                                    </span>
                                </td>
                                <td>#<?php echo $log['id_registro']; ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" 
                                            onclick="mostrarDetalhesLog(<?php echo htmlspecialchars(json_encode($log)); ?>)">
                                        <i class="fas fa-eye"></i> Ver
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal para detalhes do log -->
    <div id="logModal" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h2>Detalhes do Log</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <div id="logDetails"></div>
            </div>
        </div>
    </div>

    <script>
        function mostrarDetalhesLog(log) {
            const modalBody = document.getElementById('logDetails');
            let html = `
                <div class="log-details">
                    <div class="detail-row">
                        <strong>Data/Hora:</strong> ${new Date(log.data_hora).toLocaleString('pt-BR')}
                    </div>
                    <div class="detail-row">
                        <strong>Usuário:</strong> ${log.usuario_nome} (${log.usuario_email})
                    </div>
                    <div class="detail-row">
                        <strong>Ação:</strong> <span class="status-badge ${log.acao === 'INSERT' ? 'status-active' : log.acao === 'UPDATE' ? 'status-warning' : 'status-error'}">${log.acao}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Tabela:</strong> ${log.tabela_afetada}
                    </div>
                    <div class="detail-row">
                        <strong>ID do Registro:</strong> ${log.id_registro}
                    </div>
                    <div class="detail-row">
                        <strong>IP:</strong> ${log.ip_usuario}
                    </div>
            `;

            if (log.dados_anteriores) {
                html += `
                    <div class="detail-section">
                        <h3>Dados Anteriores:</h3>
                        <pre class="log-data">${log.dados_anteriores}</pre>
                    </div>
                `;
            }

            if (log.dados_novos) {
                html += `
                    <div class="detail-section">
                        <h3>Dados Novos:</h3>
                        <pre class="log-data">${log.dados_novos}</pre>
                    </div>
                `;
            }

            html += `</div>`;
            modalBody.innerHTML = html;
            document.getElementById('logModal').style.display = 'block';
        }

        // Fechar modal
        document.querySelectorAll('.close').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('logModal').style.display = 'none';
            });
        });

        window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('logModal')) {
                document.getElementById('logModal').style.display = 'none';
            }
        });
        // Sistema de Tema Claro/Escuro
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const body = document.body;

    // Verificar tema salvo ou preferência do sistema
    const savedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    // Aplicar tema inicial
    if (savedTheme === 'light' || (!savedTheme && !systemPrefersDark)) {
        enableLightMode();
    } else {
        enableDarkMode();
    }

    // Event listener para o botão de toggle
    themeToggle.addEventListener('click', function() {
        if (body.getAttribute('data-theme') === 'light') {
            enableDarkMode();
        } else {
            enableLightMode();
        }
    });

    function enableLightMode() {
        body.setAttribute('data-theme', 'light');
        themeIcon.className = 'fas fa-sun';
        themeToggle.title = 'Alternar para modo escuro';
        localStorage.setItem('theme', 'light');
    }

    function enableDarkMode() {
        body.removeAttribute('data-theme');
        themeIcon.className = 'fas fa-moon';
        themeToggle.title = 'Alternar para modo claro';
        localStorage.setItem('theme', 'dark');
    }

    // Observar mudanças na preferência do sistema
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (!localStorage.getItem('theme')) {
            if (e.matches) {
                enableDarkMode();
            } else {
                enableLightMode();
            }
        }
    });
});
    </script>
</body>
</html>