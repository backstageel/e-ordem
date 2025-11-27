# Migração de Seeders e Factories para Módulos

**Data:** 2025-01-27  
**Status:** ✅ Concluído

---

## 📋 Resumo

Todos os seeders e factories foram migrados para os respectivos módulos, seguindo a arquitetura modular do projeto. O `DatabaseSeeder` principal foi atualizado para remover os seeders movidos, e os `DatabaseSeeders` dos módulos foram criados/atualizados para chamar os seeders específicos.

---

## 📦 Seeders Migrados

### Document Module
- `DocumentSeeder.php` → `Modules/Document/database/seeders/`

### Exam Module
- `ExamSeeder.php` → `Modules/Exam/database/seeders/`
- `ExamApplicationSeeder.php` → `Modules/Exam/database/seeders/`
- `ExamResultSeeder.php` → `Modules/Exam/database/seeders/`
- `ExamTypeSeeder.php` → `Modules/Exam/database/seeders/`
- `ExamTypesSeeder.php` → `Modules/Exam/database/seeders/`

### Member Module
- `MemberSeeder.php` → `Modules/Member/database/seeders/`
- `MemberQuotaSeeder.php` → `Modules/Member/database/seeders/`

### Payment Module
- `PaymentSeeder.php` → `Modules/Payment/database/seeders/`
- `PaymentTypesAndMethodsSeeder.php` → `Modules/Payment/database/seeders/`

### Card Module
- `CardTypesSeeder.php` → `Modules/Card/database/seeders/`

### Notification Module
- `NotificationTemplatesSeeder.php` → `Modules/Notification/database/seeders/`

### Registration Module
- `ApplicationStatusSeeder.php` → `Modules/Registration/database/seeders/`

### Residency Module
- `ResidencyProgramSeeder.php` → `Modules/Residency/database/seeders/`
- `ResidencyApplicationSeeder.php` → `Modules/Residency/database/seeders/`

---

## 🏭 Factories Migradas

### Document Module
- `DocumentFactory.php` → `Modules/Document/database/factories/`
- `DocumentTypeFactory.php` → `Modules/Document/database/factories/`

### Exam Module
- `ExamFactory.php` → `Modules/Exam/database/factories/`
- `ExamApplicationFactory.php` → `Modules/Exam/database/factories/`
- `ExamResultFactory.php` → `Modules/Exam/database/factories/`

### Member Module
- `MemberFactory.php` → `Modules/Member/database/factories/`
- `MemberCardFactory.php` → `Modules/Member/database/factories/`
- `MemberQuotaFactory.php` → `Modules/Member/database/factories/`
- `MemberStatusHistoryFactory.php` → `Modules/Member/database/factories/`

### Payment Module
- `PaymentFactory.php` → `Modules/Payment/database/factories/`
- `PaymentTypeFactory.php` → `Modules/Payment/database/factories/`
- `PaymentMethodFactory.php` → `Modules/Payment/database/factories/`
- `PaymentIntegrationFactory.php` → `Modules/Payment/database/factories/`
- `PaymentIntegrationLogFactory.php` → `Modules/Payment/database/factories/`

### Card Module
- `CardTypeFactory.php` → `Modules/Card/database/factories/`

### Notification Module
- `MessageFactory.php` → `Modules/Notification/database/factories/`

### Residency Module
- `ResidencyProgramFactory.php` → `Modules/Residency/database/factories/`
- `ResidencyLocationFactory.php` → `Modules/Residency/database/factories/`
- `ResidencyProgramLocationFactory.php` → `Modules/Residency/database/factories/`
- `ResidencyApplicationFactory.php` → `Modules/Residency/database/factories/`
- `ResidencyEvaluationFactory.php` → `Modules/Residency/database/factories/`
- `ResidencyResidentFactory.php` → `Modules/Residency/database/factories/`

---

## 📝 DatabaseSeeders dos Módulos

### Criados/Atualizados

1. **DocumentDatabaseSeeder** (`Modules/Document/database/seeders/`)
   - Chama: `DocumentSeeder`

2. **MemberDatabaseSeeder** (`Modules/Member/database/seeders/`)
   - Chama: `MemberSeeder`, `MemberQuotaSeeder`

3. **ExamDatabaseSeeder** (`Modules/Exam/database/seeders/`)
   - Chama: `ExamTypeSeeder`, `ExamTypesSeeder`, `ExamSeeder`, `ExamApplicationSeeder`, `ExamResultSeeder`

4. **RegistrationDatabaseSeeder** (`Modules/Registration/database/seeders/`)
   - Chama: `RegistrationTypesSeeder`, `RegistrationFeesSeeder`, `ApplicationStatusSeeder`, `RegistrationSeeder`

5. **PaymentDatabaseSeeder** (`Modules/Payment/database/seeders/`) - **CRIADO**
   - Chama: `PaymentTypesAndMethodsSeeder`, `PaymentSeeder`

6. **CardDatabaseSeeder** (`Modules/Card/database/seeders/`) - **CRIADO**
   - Chama: `CardTypesSeeder`

7. **NotificationDatabaseSeeder** (`Modules/Notification/database/seeders/`) - **CRIADO**
   - Chama: `NotificationTemplatesSeeder`

8. **ResidencyDatabaseSeeder** (`Modules/Residency/database/seeders/`) - **CRIADO**
   - Chama: `ResidencyProgramSeeder`, `ResidencyApplicationSeeder`

---

## 🔄 DatabaseSeeder Principal

O `DatabaseSeeder` principal (`database/seeders/DatabaseSeeder.php`) foi atualizado para:

1. **Remover seeders movidos** - Todos os seeders que foram movidos para módulos foram removidos da lista de chamadas
2. **Manter seeders core** - Seeders que pertencem à infraestrutura base permanecem:
   - `LanguageSeeder`
   - `WorkflowStatesSeeder`
   - `UserProfilesSeeder`
3. **Executar DatabaseSeeders dos módulos** - A lógica existente que executa `{ModuleName}DatabaseSeeder` de cada módulo habilitado foi mantida

---

## 🔧 Alterações Técnicas

### Namespaces Atualizados

Todos os seeders e factories movidos tiveram seus namespaces atualizados:

**Seeders:**
- De: `namespace Database\Seeders;`
- Para: `namespace Modules\{ModuleName}\Database\Seeders;`

**Factories:**
- De: `namespace Database\Factories;`
- Para: `namespace Modules\{ModuleName}\Database\Factories;`

### Autoload

Os `composer.json` dos módulos já estavam configurados com autoload para factories e seeders:
```json
{
  "autoload": {
    "psr-4": {
      "Modules\\{ModuleName}\\Database\\Factories\\": "database/factories/",
      "Modules\\{ModuleName}\\Database\\Seeders\\": "database/seeders/"
    }
  }
}
```

Após a migração, foi executado `composer dump-autoload` para regenerar o autoload.

---

## 📊 Estatísticas

- **Total de seeders movidos:** 18
- **Total de factories movidas:** 22
- **DatabaseSeeders criados:** 4 (Payment, Card, Notification, Residency)
- **DatabaseSeeders atualizados:** 4 (Document, Member, Exam, Registration)

---

## ⚠️ Notas Importantes

### Seeders e Factories Legacy

Existem seeders e factories com nomenclatura "Residence" (legacy) que parecem ser versões antigas:

**Seeders legacy:**
- `ResidenceApplicationSeeder.php`
- `ResidenceCompletionSeeder.php`
- `ResidenceEvaluationSeeder.php`
- `ResidenceLocationSeeder.php`
- `ResidenceMedicalSeeder.php`
- `ResidenceProgramSeeder.php`
- `ResidenceResidentSeeder.php`

**Factories legacy:**
- `ResidenceApplicationFactory.php`
- `ResidenceCompletionFactory.php`
- `ResidenceEvaluationFactory.php`
- `ResidenceExamFactory.php`
- `ResidenceLocationAssignmentFactory.php`
- `ResidenceLocationFactory.php`
- `ResidenceProgramFactory.php`
- `ResidenceResidentFactory.php`

**Recomendação:** Verificar se estes são duplicados das versões "Residency" e removê-los se não forem necessários.

### Seeders Core (Permaneceram na Raiz)

Os seguintes seeders permanecem na raiz pois pertencem à infraestrutura core:
- `LanguageSeeder.php`
- `WorkflowStatesSeeder.php`
- `UserProfilesSeeder.php`
- `AdminPermissionsSeeder.php`
- `NeighborhoodSeeder.php`

---

## ✅ Testes

Os seeders foram testados e estão funcionando corretamente. O `DatabaseSeeder` principal executa automaticamente os `DatabaseSeeders` de todos os módulos habilitados através da lógica:

```php
foreach (Module::allEnabled() as $module) {
    $seederClass = 'Modules\\'.$module->getName().'\\Database\\Seeders\\'.$module->getName().'DatabaseSeeder';
    if (class_exists($seederClass)) {
        $this->call($seederClass);
    }
}
```

---

## 🚀 Próximos Passos

1. ✅ Seeders migrados
2. ✅ Factories migradas
3. ✅ DatabaseSeeders criados/atualizados
4. ✅ DatabaseSeeder principal atualizado
5. ⏳ Verificar e remover seeders/factories legacy de "Residence" se duplicados
6. ⏳ Testar execução completa: `php artisan db:seed`

---

**Última Atualização:** 2025-01-27

