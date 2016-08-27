#TODO

- Add icons support for BaseModel

- Version 1.8.0 - Repeatable Fields
    - Develop methods for WPExpress/UI/FieldCollection repeatable fields
    
    
- Version 1.7.0 - Additional field methods
    - Develop methods for WPExpress/UI/FieldCollection
        - addNumberInput
        - addURLInput
        - addEmailInput
        - addGoogleMap
        - addRichTextArea
        - addCodeInput
        - addCalendar
        

- Version 1.6.0 
    - Develop methods for UserRole class
        - insert
        - save
        - delete
        - get
        - getAll
        - getUsers
        - addMember
        - removeMember
    - Develop methods for BaseUser class
        - getMetaFields
        - getCurrent
        - getRoles
        - addRole
        - removeRole
        - makeAdmin
        - getSites


-Version 1.5.0 - WPExpress/Query 1.0.0
    - Database
        - Users and UserRole
        - Refactorize Query and Taxonomy classes
    - WPExpress/Model
        - BaseUser
        - BaseUserRole    
        - Add automatic nonce security
        - Restrict access to fields by user ID, login, email, Role or capabilities
        - Implement method disableAjaxUpdate
        - Implement Asynchronous field update
        - Add javascript field validation
