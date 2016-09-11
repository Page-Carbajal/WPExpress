#TODO

    
-Version 2.B.0 - WPExpress/Query 1.0.0
    - Database
        - Users and UserRole
        - Refactorize Query and Taxonomy classes
    - WPExpress
        - Add BaseUser
            - getMetaFields
            - getCurrent
            - getRoles
            - addRole
            - removeRole
            - makeAdmin
            - getSites
        - Add BaseUserRole
            - insert
            - save
            - delete
            - get
            - getAll
            - getUsers
            - addMember
            - removeMember
        - Admin/SettingsPage
            - Add nonce
        - BaseModel 
            - Develop all traversing methods
            - Implement sortable table view columns
            - Implement format for table view columns
            - Implement method to register taxonomies from BaseModel
            - Implement PostTypeLabels collection
        - Finish BaseTaxonomy class
        
        
- Version 2.A.0 - Additional field methods
    - Admin
        - Add BaseWidget class
    - Implement Support for CMB2 / getValues and Traversing
    - Implement Support for ACF / getValues and Traversing
    - BaseModel
        - Add icons for WordPress menu bar
        - Implement method disableAjaxUpdate
        - Implement Asynchronous field update
        - Restrict access to fields and metaboxes by user ID, login, email, Role or capabilities
    - Develop methods for WPExpress/UI/FieldCollection
        - addNumberInput 
        - addURLInput
        - addEmailInput
        - addMapField
        - addRichTextArea
        - addDatePicker
    - Develop methods for WPExpress/UI/FieldCollection repeatable fields
    - Add field validations
    - Use React to render fields 
                